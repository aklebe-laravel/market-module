<?php

namespace Modules\Market\app\Listeners;

use Modules\Acl\app\Models\AclGroup;
use Modules\Market\app\Models\User as MarketUser;

class ImportRowUser extends ImportRowMarket
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        parent::__construct();

        $this->requiredTypesSingular[] = 'user';
    }

    /**
     * Handle the event.
     *
     * @param  \Modules\DeployEnv\app\Events\ImportRow  $event
     * @return bool  false to stop all following listeners
     */
    public function handle(\Modules\DeployEnv\app\Events\ImportRow $event): bool
    {
        if (!$this->isRequiredType($event->type)) {
            return true;
        }

        $id = data_get($event->row, 'id');
        $email = data_get($event->row, 'email');
        $name = data_get($event->row, 'name');
        if (!$id && !$email && !$name) {
            return true;
        }

        // get User by id or sku
        $user = app(MarketUser::class)::with([]);
        if ($id) {
            $user->where('id', $id);
        } elseif ($email) {
            $user->where('email', $email);
        } elseif ($name) {
            $user->where('name', $name);
        }

        // validate data
        $validated = $this->validateRow($event->row);
        if (empty($validated['password'])) {
            $validated['password'] = '1234567';
        }

        // save the base User
        /** @var MarketUser $user */
        $user = $user->first();
        if ($user) {
            // Log::debug("User found.");
            $this->setExtraAttributes($user, $event->row);
            $user->update($validated);
        } else {
            // Log::debug("User not found.");
            $validated['shared_id'] = uniqid('js_suid_');
            /** @var MarketUser $user */
            if ($user = MarketUser::create($validated)) {
                $this->setExtraAttributes($user, $event->row);
                $user->saveModelAttributeTypeValues();
            }
        }

        if (!$user || !$user->getKey()) {
            return true;
        }

        // save the User relations
        $this->doMediaRelations($user, $event->row);
        $this->doUserRolesRelations($user, $event->row);

        return true;
    }

    /**
     * @param $row
     * @return array
     */
    protected function validateRow(&$row): array
    {
        $validatedRow = parent::validateRow($row);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'is_enabled', default: true);
        $this->addBasicColumnIfPresent($row, $validatedRow, 'email');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'name');
        $this->addBasicColumnIfPresent($row, $validatedRow, 'password');
        return $validatedRow;
    }

    /**
     * @param  MarketUser  $user
     * @param  array  $row
     * @return void
     */
    private function doUserRolesRelations(MarketUser $user, array &$row)
    {
        if (!($aclGroups = data_get($row, 'acl_groups'))) {
            return;
        }

        if (!($aclGroups = explode(',', $aclGroups))) {
            return;
        }

        $aclGroupIds = [];
        foreach ($aclGroups as $aclGroupId) {
            $aclGroup = AclGroup::with([]);
            if (is_numeric($aclGroupId)) {
                $aclGroup = $aclGroup->find($aclGroupId)->first();
            } else {
                $aclGroup = $aclGroup->where('name', $aclGroupId)->first();
            }
            if ($aclGroup) {
                $aclGroupIds[] = $aclGroup->getKey();
            }
        }

        $user->aclGroups()->sync($aclGroupIds);
    }

}

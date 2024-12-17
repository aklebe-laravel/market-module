<?php

namespace Modules\Market\app\Listeners;

use Modules\Acl\app\Models\AclGroup;
use Modules\DeployEnv\app\Events\ImportRow;
use Modules\Market\app\Models\User as MarketUser;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
     * @param  ImportRow  $event
     *
     * @return bool  true to accept this data for this type
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(ImportRow $event): bool
    {
        parent::handle($event);

        if (!$this->isRequiredType($event->importContentEvent->type)) {
            return false;
        }

        $id = data_get($event->row, 'id');
        $email = data_get($event->row, 'email');
        $name = data_get($event->row, 'name');
        if (!$id && !$email && !$name) {
            return false;
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
            return false;
        }

        // save the User relations
        $this->doMediaRelations($user, $event->row);
        $this->doUserRolesRelations($user, $event->row);

        return true;
    }

    /**
     * @param $row
     *
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
     * @param  array       $row
     *
     * @return void
     */
    private function doUserRolesRelations(MarketUser $user, array &$row): void
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

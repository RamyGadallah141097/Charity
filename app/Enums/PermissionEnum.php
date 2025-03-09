<?php

namespace App\Enums;

enum PermissionEnum: string
{
    // الرئيسية
    case ADMIN_HOME = 'admin.home';

    // المشرفين
    case ADMINS_INDEX = 'admins.index';
    case ADMINS_CREATE = 'admins.create';
    case ADMINS_STORE = 'admins.store';
    case ADMINS_EDIT = 'admins.edit';
    case ADMINS_UPDATE = 'admins.update';
    case ADMINS_DESTROY = 'admins.destroy';
    case DELETE_ADMIN = 'delete_admin';
    case MY_PROFILE = 'myProfile';

    // المستفيدين
    case USERS_INDEX = 'users.index';
    case USERS_CREATE = 'users.create';
    case USERS_STORE = 'users.store';
    case DELETE_USERS = 'delete_users';
    case UPDATE_USER_STATUS = 'updateUserStatus';
    case USER_DETAILS = 'userDetails';
    case DONATION_DETAILS = 'DonationDetails';

    // المتبرعين
    case DONORS_INDEX = 'donors.index';
    case DONORS_CREATE = 'donors.create';
    case DONORS_STORE = 'donors.store';
    case DONORS_EDIT = 'donors.edit';
    case DONORS_UPDATE = 'donors.update';
    case DONORS_DESTROY = 'donors.destroy';
    case DELETE_DONORS = 'delete_donors';
    case DONATIONS_DELETE = 'donations_delete';
    case DONATIONS_INDEX = 'Donations.index';
    case DONATIONS_CREATE = 'Donations.create';
    case DONATIONS_STORE = 'Donations.store';
    case DONATIONS_EDIT = 'Donations.edit';
    case DONATIONS_UPDATE = 'Donations.update';
    case DONATIONS_DESTROY = 'Donations.destroy';
    case GET_DONOR_PHONE = 'get_donor_phone';
    case SEARCH_DONOR = 'search.donor';

    // المهام
    case TASKS_INDEX = 'tasks.index';
    case TASKS_CREATE = 'tasks.create';
    case TASKS_STORE = 'tasks.store';
    case TASKS_EDIT = 'tasks.edit';
    case TASKS_UPDATE = 'tasks.update';
    case TASKS_DESTROY = 'tasks.destroy';
    case DELETE_TASK = 'delete_task';

    // الإعدادات
    case SETTING_INDEX = 'setting.index';
    case SETTING_UPDATE = 'settingUpdate';

    // المصادقة
    case ADMIN_LOGOUT = 'admin.logout';
    case ADMIN_LOGIN = 'admin.login';

    // الصلاحيات
    case ROLES_INDEX = 'roles.index';
    case ROLES_CREATE = 'roles.create';
    case ROLES_STORE = 'roles.store';
    case ROLES_EDIT = 'roles.edit';
    case ROLES_UPDATE = 'roles.update';
    case ROLES_DESTROY = 'roles.destroy';
    case ROLE_DELETE = 'Role_delete';

    // دالة للحصول على كل القيم كـ Array
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

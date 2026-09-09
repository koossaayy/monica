<?php

namespace App\Domains\Settings\CreateAccount\Jobs;

use App\Domains\Settings\ManageAddressTypes\Services\CreateAddressType;
use App\Domains\Settings\ManageCallReasons\Services\CreateCallReason;
use App\Domains\Settings\ManageCallReasons\Services\CreateCallReasonType;
use App\Domains\Settings\ManageContactInformationTypes\Services\CreateContactInformationType;
use App\Domains\Settings\ManageGenders\Services\CreateGender;
use App\Domains\Settings\ManageGroupTypes\Services\CreateGroupType;
use App\Domains\Settings\ManageGroupTypes\Services\CreateGroupTypeRole;
use App\Domains\Settings\ManageNotificationChannels\Services\CreateUserNotificationChannel;
use App\Domains\Settings\ManagePetCategories\Services\CreatePetCategory;
use App\Domains\Settings\ManagePostTemplates\Services\CreatePostTemplate;
use App\Domains\Settings\ManagePostTemplates\Services\CreatePostTemplateSection;
use App\Domains\Settings\ManagePronouns\Services\CreatePronoun;
use App\Domains\Settings\ManageRelationshipTypes\Services\CreateRelationshipGroupType;
use App\Domains\Settings\ManageTemplates\Services\AssociateModuleToTemplatePage;
use App\Domains\Settings\ManageTemplates\Services\CreateModule;
use App\Domains\Settings\ManageTemplates\Services\CreateTemplate;
use App\Domains\Settings\ManageTemplates\Services\CreateTemplatePage;
use App\Interfaces\ServiceInterface;
use App\Models\Currency;
use App\Models\Emotion;
use App\Models\Module;
use App\Models\RelationshipGroupType;
use App\Models\RelationshipType;
use App\Models\Template;
use App\Models\TemplatePage;
use App\Models\UserNotificationChannel;
use App\Services\QueuableService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SetupAccount extends QueuableService implements ServiceInterface
{
    /**
     * The template instance.
     *
     * @var Template
     */
    protected $template;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'account_id' => 'required|uuid|exists:accounts,id',
            'author_id' => 'required|uuid|exists:users,id',
        ];
    }

    /**
     * Get the permissions that apply to the user calling the service.
     */
    public function permissions(): array
    {
        return [
            'author_must_belong_to_account',
        ];
    }

    /**
     * Execute the service.
     */
    public function execute(array $data): void
    {
        $this->validateRules($data);

        $this->populateCurrencies();
        $this->addNotificationChannel();
        $this->addTemplate();
        $this->addTemplatePageContactInformation();
        $this->addTemplatePageFeed();
        $this->addTemplatePageContact();
        $this->addTemplatePageSocial();
        $this->addTemplatePageLifeEvents();
        $this->addTemplatePageInformation();
        $this->addFirstInformation();
    }

    /**
     * Populate currencies in the account.
     */
    private function populateCurrencies(): void
    {
        $currencies = Currency::get();
        foreach ($currencies as $currency) {
            $this->account()->currencies()->attach($currency->id);
        }
    }

    /**
     * Add the first notification channel based on the email address of the user.
     */
    private function addNotificationChannel(): void
    {
        $channel = (new CreateUserNotificationChannel)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'label' => __('Email address'),
            'type' => UserNotificationChannel::TYPE_EMAIL,
            'content' => $this->author->email,
            'verify_email' => false,
            'preferred_time' => '09:00',
        ]);

        $channel->verified_at = Carbon::now();
        $channel->active = true;
        $channel->save();
    }

    /**
     * Add the first template.
     */
    private function addTemplate(): void
    {
        $request = [
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name' => null,
            'name_translation_key' => trans_key(__('Default template')),
            'can_be_deleted' => false,
        ];

        $this->template = (new CreateTemplate)->execute($request);
    }

    private function addTemplatePageContactInformation(): void
    {
        // the contact information page is automatically created when we
        // create the template
        $templatePageContact = $this->template->pages()
            ->firstWhere('type', TemplatePage::TYPE_CONTACT);

        // avatar
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Avatar')),
            'type' => Module::TYPE_AVATAR,
            'can_be_deleted' => false,
            'reserved_to_contact_information' => true,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageContact->id,
            'module_id' => $module->id,
        ]);

        // names
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Contact name')),
            'type' => Module::TYPE_CONTACT_NAMES,
            'can_be_deleted' => false,
            'reserved_to_contact_information' => true,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageContact->id,
            'module_id' => $module->id,
        ]);

        // family summary
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Family summary')),
            'type' => Module::TYPE_FAMILY_SUMMARY,
            'can_be_deleted' => false,
            'reserved_to_contact_information' => true,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageContact->id,
            'module_id' => $module->id,
        ]);

        // important dates
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Important dates')),
            'type' => Module::TYPE_IMPORTANT_DATES,
            'can_be_deleted' => false,
            'reserved_to_contact_information' => true,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageContact->id,
            'module_id' => $module->id,
        ]);

        // gender/pronouns
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Gender and pronoun')),
            'type' => Module::TYPE_GENDER_PRONOUN,
            'can_be_deleted' => false,
            'reserved_to_contact_information' => true,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageContact->id,
            'module_id' => $module->id,
        ]);

        // labels
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Labels')),
            'type' => Module::TYPE_LABELS,
            'can_be_deleted' => false,
            'reserved_to_contact_information' => true,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageContact->id,
            'module_id' => $module->id,
        ]);

        // companies
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Job information')),
            'type' => Module::TYPE_COMPANY,
            'can_be_deleted' => false,
            'reserved_to_contact_information' => true,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageContact->id,
            'module_id' => $module->id,
        ]);

        // religions
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Religions')),
            'type' => Module::TYPE_RELIGIONS,
            'can_be_deleted' => false,
            'reserved_to_contact_information' => true,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageContact->id,
            'module_id' => $module->id,
        ]);
    }

    private function addTemplatePageFeed(): void
    {
        $templatePageFeed = (new CreateTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'name_translation_key' => trans_key(__('Activity feed')),
            'can_be_deleted' => true,
        ]);
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Contact feed')),
            'type' => Module::TYPE_FEED,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageFeed->id,
            'module_id' => $module->id,
        ]);
    }

    private function addTemplatePageContact(): void
    {
        $template = (new CreateTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'name_translation_key' => trans_key(__('Ways to connect')),
            'can_be_deleted' => true,
        ]);

        // Addresses
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Addresses')),
            'type' => Module::TYPE_ADDRESSES,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $template->id,
            'module_id' => $module->id,
        ]);

        // Contact information
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Contact information')),
            'type' => Module::TYPE_CONTACT_INFORMATION,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $template->id,
            'module_id' => $module->id,
        ]);
    }

    private function addTemplatePageSocial(): void
    {
        $templatePageSocial = (new CreateTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'name_translation_key' => trans_key(__('Social')),
            'can_be_deleted' => true,
        ]);

        // Relationships
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Relationships')),
            'type' => Module::TYPE_RELATIONSHIPS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageSocial->id,
            'module_id' => $module->id,
        ]);

        // Pets
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Pets')),
            'type' => Module::TYPE_PETS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageSocial->id,
            'module_id' => $module->id,
        ]);

        // Groups
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Groups')),
            'type' => Module::TYPE_GROUPS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageSocial->id,
            'module_id' => $module->id,
        ]);
    }

    private function addTemplatePageLifeEvents(): void
    {
        $templatePageSocial = (new CreateTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'name_translation_key' => trans_key(__('Life & goals')),
            'can_be_deleted' => true,
        ]);

        // life events
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Life')),
            'type' => Module::TYPE_LIFE_EVENTS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageSocial->id,
            'module_id' => $module->id,
        ]);

        // goals
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Goals')),
            'type' => Module::TYPE_GOALS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageSocial->id,
            'module_id' => $module->id,
        ]);
    }

    private function addTemplatePageInformation(): void
    {
        $templatePageInformation = (new CreateTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'name_translation_key' => trans_key(__('Information')),
            'can_be_deleted' => true,
        ]);

        // Documents
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Documents')),
            'type' => Module::TYPE_DOCUMENTS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageInformation->id,
            'module_id' => $module->id,
        ]);

        // Documents
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Photos')),
            'type' => Module::TYPE_PHOTOS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageInformation->id,
            'module_id' => $module->id,
        ]);

        // Notes
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Notes')),
            'type' => Module::TYPE_NOTES,
            'can_be_deleted' => false,
            'pagination' => 3,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageInformation->id,
            'module_id' => $module->id,
        ]);

        // Reminders
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Reminders')),
            'type' => Module::TYPE_REMINDERS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageInformation->id,
            'module_id' => $module->id,
        ]);

        // Loans
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Loans')),
            'type' => Module::TYPE_LOANS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageInformation->id,
            'module_id' => $module->id,
        ]);

        // Tasks
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Tasks')),
            'type' => Module::TYPE_TASKS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageInformation->id,
            'module_id' => $module->id,
        ]);

        // Calls
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Calls')),
            'type' => Module::TYPE_CALLS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageInformation->id,
            'module_id' => $module->id,
        ]);

        // Posts
        $module = (new CreateModule)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Posts')),
            'type' => Module::TYPE_POSTS,
            'can_be_deleted' => false,
        ]);
        (new AssociateModuleToTemplatePage)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'template_id' => $this->template->id,
            'template_page_id' => $templatePageInformation->id,
            'module_id' => $module->id,
        ]);
    }

    private function addFirstInformation(): void
    {
        $this->addGenders();
        $this->addPronouns();
        $this->addGroupTypes();
        $this->addRelationshipTypes();
        $this->addAddressTypes();
        $this->addCallReasonTypes();
        $this->addContactInformation();
        $this->addPetCategories();
        $this->addEmotions();
        $this->addGiftOccasions();
        $this->addGiftStates();
        $this->addPostTemplates();
        $this->addReligions();
    }

    /**
     * Add the default genders in the account.
     */
    private function addGenders(): void
    {
        $types = collect([
            trans_key(__('Male')),
            trans_key(__('Female')),
            trans_key(__('Other')),
        ]);

        foreach ($types as $type) {
            $request = [
                'account_id' => $this->author->account_id,
                'author_id' => $this->author->id,
                'name_translation_key' => $type,
            ];

            (new CreateGender)->execute($request);
        }
    }

    /**
     * Add the default pronouns in the account.
     */
    private function addPronouns(): void
    {
        $pronouns = collect([
            trans_key('he/him'),
            trans_key('she/her'),
            trans_key('they/them'),
            trans_key('per/per'),
            trans_key('ve/ver'),
            trans_key('xe/xem'),
            trans_key('ze/hir'),
        ]);

        foreach ($pronouns as $pronoun) {
            $request = [
                'account_id' => $this->author->account_id,
                'author_id' => $this->author->id,
                'name_translation_key' => $pronoun,
            ];

            (new CreatePronoun)->execute($request);
        }
    }

    /**
     * Add the default group types in the account.
     */
    private function addGroupTypes(): void
    {
        $groupType = (new CreateGroupType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'label_translation_key' => trans_key(__('Family')),
        ]);
        (new CreateGroupTypeRole)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'group_type_id' => $groupType->id,
            'label_translation_key' => trans_key(__('Parent')),
        ]);
        (new CreateGroupTypeRole)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'group_type_id' => $groupType->id,
            'label_translation_key' => trans_key(__('Child')),
        ]);

        $groupType = (new CreateGroupType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'label_translation_key' => trans_key(__('Couple')),
        ]);
        (new CreateGroupTypeRole)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'group_type_id' => $groupType->id,
            'label_translation_key' => trans_key(__('Partner')),
        ]);

        $groupType = (new CreateGroupType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'label_translation_key' => trans_key(__('Club')),
        ]);

        $groupType = (new CreateGroupType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'label_translation_key' => trans_key(__('Association')),
        ]);

        $groupType = (new CreateGroupType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'label_translation_key' => trans_key(__('Roomates')),
        ]);
    }

    private function addRelationshipTypes(): void
    {
        // Love type
        $group = (new CreateRelationshipGroupType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Love')),
            'can_be_deleted' => false,
            'type' => RelationshipGroupType::TYPE_LOVE,
        ]);

        DB::table('relationship_types')->insert([
            [
                'name_translation_key' => trans_key(__('significant other')),
                'name_reverse_relationship_translation_key' => trans_key(__('significant other')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => false,
                'type' => RelationshipType::TYPE_LOVE,
            ],
            [
                'name_translation_key' => trans_key(__('spouse')),
                'name_reverse_relationship_translation_key' => trans_key(__('spouse')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => false,
                'type' => RelationshipType::TYPE_LOVE,
            ],
            [
                'name_translation_key' => trans_key(__('date')),
                'name_reverse_relationship_translation_key' => trans_key(__('date')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
            [
                'name_translation_key' => trans_key(__('lover')),
                'name_reverse_relationship_translation_key' => trans_key(__('lover')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
            [
                'name_translation_key' => trans_key(__('in love with')),
                'name_reverse_relationship_translation_key' => trans_key(__('loved by')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
            [
                'name_translation_key' => trans_key(__('ex-boyfriend')),
                'name_reverse_relationship_translation_key' => trans_key(__('ex-boyfriend')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
        ]);

        // Family type
        $group = (new CreateRelationshipGroupType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Family')),
            'can_be_deleted' => false,
            'type' => RelationshipGroupType::TYPE_FAMILY,
        ]);

        DB::table('relationship_types')->insert([
            [
                'name_translation_key' => trans_key(__('parent')),
                'name_reverse_relationship_translation_key' => trans_key(__('child')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => false,
                'type' => RelationshipType::TYPE_CHILD,
            ],
            [
                'name_translation_key' => trans_key('brother/sister'),
                'name_reverse_relationship_translation_key' => trans_key('brother/sister'),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
            [
                'name_translation_key' => trans_key(__('grand parent')),
                'name_reverse_relationship_translation_key' => trans_key(__('grand child')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
            [
                'name_translation_key' => trans_key('uncle/aunt'),
                'name_reverse_relationship_translation_key' => trans_key('nephew/niece'),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
            [
                'name_translation_key' => trans_key(__('cousin')),
                'name_reverse_relationship_translation_key' => trans_key(__('cousin')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
            [
                'name_translation_key' => trans_key(__('godparent')),
                'name_reverse_relationship_translation_key' => trans_key(__('godchild')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
        ]);

        // Friend
        $group = (new CreateRelationshipGroupType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Friend')),
            'can_be_deleted' => true,
        ]);

        DB::table('relationship_types')->insert([
            [
                'name_translation_key' => trans_key(__('friend')),
                'name_reverse_relationship_translation_key' => trans_key(__('friend')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
            [
                'name_translation_key' => trans_key(__('best friend')),
                'name_reverse_relationship_translation_key' => trans_key(__('best friend')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
        ]);

        // Work
        $group = (new CreateRelationshipGroupType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Work')),
            'can_be_deleted' => true,
        ]);

        DB::table('relationship_types')->insert([
            [
                'name_translation_key' => trans_key(__('colleague')),
                'name_reverse_relationship_translation_key' => trans_key(__('colleague')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
            [
                'name_translation_key' => trans_key(__('subordinate')),
                'name_reverse_relationship_translation_key' => trans_key(__('boss')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
            [
                'name_translation_key' => trans_key(__('mentor')),
                'name_reverse_relationship_translation_key' => trans_key(__('protege')),
                'relationship_group_type_id' => $group->id,
                'can_be_deleted' => true,
                'type' => null,
            ],
        ]);
    }

    private function addAddressTypes(): void
    {
        $addresses = collect([
            [
                'type' => 'home',
                'label' => trans_key(__('🏡 Home')),
            ],
            [
                'type' => 'secondary',
                'label' => trans_key(__('🏠 Secondary residence')),
            ],
            [
                'type' => 'work',
                'label' => trans_key(__('🏢 Work')),
            ],
            [
                'type' => 'chalet',
                'label' => trans_key(__('🌳 Chalet')),
            ],
            [
                'type' => 'other',
                'label' => trans_key(__('❔ Other')),
            ],
        ]);

        foreach ($addresses as $address) {
            (new CreateAddressType)->execute([
                'account_id' => $this->author->account_id,
                'author_id' => $this->author->id,
                'name_translation_key' => $address['label'],
                'type' => $address['type'],
            ]);
        }
    }

    private function addCallReasonTypes(): void
    {
        $type = (new CreateCallReasonType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'label_translation_key' => trans_key(__('Personal')),
        ]);
        (new CreateCallReason)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'call_reason_type_id' => $type->id,
            'label_translation_key' => trans_key(__('For advice')),
        ]);
        (new CreateCallReason)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'call_reason_type_id' => $type->id,
            'label_translation_key' => trans_key(__('Just to say hello')),
        ]);
        (new CreateCallReason)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'call_reason_type_id' => $type->id,
            'label_translation_key' => trans_key(__('To see if they need anything')),
        ]);
        (new CreateCallReason)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'call_reason_type_id' => $type->id,
            'label_translation_key' => trans_key(__('Out of respect and appreciation')),
        ]);
        (new CreateCallReason)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'call_reason_type_id' => $type->id,
            'label_translation_key' => trans_key(__('To hear their story')),
        ]);

        // business
        $type = (new CreateCallReasonType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'label_translation_key' => trans_key(__('Business')),
        ]);
        (new CreateCallReason)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'call_reason_type_id' => $type->id,
            'label_translation_key' => trans_key(__('Discuss recent purchases')),
        ]);
        (new CreateCallReason)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'call_reason_type_id' => $type->id,
            'label_translation_key' => trans_key(__('Discuss partnership')),
        ]);
    }

    private function addContactInformation(): void
    {
        $information = (new CreateContactInformationType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Email address')),
            'protocol' => 'mailto:',
            'type' => 'email',
        ]);
        $information->can_be_deleted = false;
        $information->save();

        $information = (new CreateContactInformationType)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'name_translation_key' => trans_key(__('Phone')),
            'protocol' => 'tel:',
            'type' => 'phone',
        ]);
        $information->can_be_deleted = false;
        $information->save();

        foreach (config('app.social_protocols') as $socialProtocol) {
            (new CreateContactInformationType)->execute([
                'account_id' => $this->author->account_id,
                'author_id' => $this->author->id,
                'name_translation_key' => $socialProtocol['name_translation_key'],
                'type' => $socialProtocol['type'],
            ]);
        }
    }

    private function addPetCategories(): void
    {
        $categories = collect([
            trans_key(__('Dog')),
            trans_key(__('Cat')),
            trans_key(__('Bird')),
            trans_key(__('Fish')),
            trans_key(__('Small animal')),
            trans_key(__('Hamster')),
            trans_key(__('Horse')),
            trans_key(__('Rabbit')),
            trans_key(__('Rat')),
            trans_key(__('Reptile')),
        ]);

        foreach ($categories as $category) {
            (new CreatePetCategory)->execute([
                'account_id' => $this->author->account_id,
                'author_id' => $this->author->id,
                'name_translation_key' => $category,
            ]);
        }
    }

    private function addEmotions(): void
    {
        DB::table('emotions')->insert([
            [
                'account_id' => $this->author->account_id,
                'name_translation_key' => trans_key(__('😡 Negative')),
                'type' => Emotion::TYPE_NEGATIVE,
            ],
            [
                'account_id' => $this->author->account_id,
                'name_translation_key' => trans_key(__('😶‍🌫️ Neutral')),
                'type' => Emotion::TYPE_NEUTRAL,
            ],
            [
                'account_id' => $this->author->account_id,
                'name_translation_key' => trans_key(__('😁 Positive')),
                'type' => Emotion::TYPE_POSITIVE,
            ],
        ]);
    }

    private function addGiftOccasions(): void
    {
        DB::table('gift_occasions')->insert([
            [
                'account_id' => $this->author->account_id,
                'label_translation_key' => trans_key(__('Birthday')),
                'position' => 1,
            ],
            [
                'account_id' => $this->author->account_id,
                'label_translation_key' => trans_key(__('Anniversary')),
                'position' => 2,
            ],
            [
                'account_id' => $this->author->account_id,
                'label_translation_key' => trans_key(__('Christmas')),
                'position' => 3,
            ],
            [
                'account_id' => $this->author->account_id,
                'label_translation_key' => trans_key(__('Just because')),
                'position' => 4,
            ],
            [
                'account_id' => $this->author->account_id,
                'label_translation_key' => trans_key(__('Wedding')),
                'position' => 5,
            ],
        ]);
    }

    private function addGiftStates(): void
    {
        DB::table('gift_states')->insert([
            [
                'account_id' => $this->author->account_id,
                'label_translation_key' => trans_key(__('Idea')),
                'position' => 1,
            ],
            [
                'account_id' => $this->author->account_id,
                'label_translation_key' => trans_key(__('Searched')),
                'position' => 2,
            ],
            [
                'account_id' => $this->author->account_id,
                'label_translation_key' => trans_key(__('Found')),
                'position' => 3,
            ],
            [
                'account_id' => $this->author->account_id,
                'label_translation_key' => trans_key(__('Bought')),
                'position' => 4,
            ],
            [
                'account_id' => $this->author->account_id,
                'label_translation_key' => trans_key(__('Offered')),
                'position' => 5,
            ],
        ]);
    }

    private function addPostTemplates(): void
    {
        // default template
        $postTemplate = (new CreatePostTemplate)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'label_translation_key' => trans_key(__('Regular post')),
            'can_be_deleted' => false,
        ]);

        (new CreatePostTemplateSection)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'post_template_id' => $postTemplate->id,
            'label_translation_key' => trans_key(__('Content')),
            'can_be_deleted' => false,
        ]);

        // inspirational template
        $postTemplate = (new CreatePostTemplate)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'label_translation_key' => trans_key(__('Inspirational post')),
            'can_be_deleted' => true,
        ]);

        (new CreatePostTemplateSection)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'post_template_id' => $postTemplate->id,
            'label_translation_key' => trans_key(__('I am grateful for')),
            'can_be_deleted' => true,
        ]);
        (new CreatePostTemplateSection)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'post_template_id' => $postTemplate->id,
            'label_translation_key' => trans_key(__('Daily affirmation')),
            'can_be_deleted' => true,
        ]);
        (new CreatePostTemplateSection)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'post_template_id' => $postTemplate->id,
            'label_translation_key' => trans_key(__('How could I have done this day better?')),
            'can_be_deleted' => true,
        ]);
        (new CreatePostTemplateSection)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'post_template_id' => $postTemplate->id,
            'label_translation_key' => trans_key(__('What would make today great?')),
            'can_be_deleted' => true,
        ]);
        (new CreatePostTemplateSection)->execute([
            'account_id' => $this->author->account_id,
            'author_id' => $this->author->id,
            'post_template_id' => $postTemplate->id,
            'label_translation_key' => trans_key(__('Three things that happened today')),
            'can_be_deleted' => true,
        ]);
    }

    private function addReligions(): void
    {
        DB::table('religions')->insert([
            [
                'account_id' => $this->author->account_id,
                'translation_key' => trans_key(__('Christian')),
                'position' => 1,
            ],
            [
                'account_id' => $this->author->account_id,
                'translation_key' => trans_key(__('Muslim')),
                'position' => 2,
            ],
            [
                'account_id' => $this->author->account_id,
                'translation_key' => trans_key(__('Hinduist')),
                'position' => 3,
            ],
            [
                'account_id' => $this->author->account_id,
                'translation_key' => trans_key(__('Buddhist')),
                'position' => 4,
            ],
            [
                'account_id' => $this->author->account_id,
                'translation_key' => trans_key(__('Shintoist')),
                'position' => 5,
            ],
            [
                'account_id' => $this->author->account_id,
                'translation_key' => trans_key(__('Taoist')),
                'position' => 6,
            ],
            [
                'account_id' => $this->author->account_id,
                'translation_key' => trans_key(__('Sikh')),
                'position' => 7,
            ],
            [
                'account_id' => $this->author->account_id,
                'translation_key' => trans_key(__('Jew')),
                'position' => 8,
            ],
            [
                'account_id' => $this->author->account_id,
                'translation_key' => trans_key(__('Atheist')),
                'position' => 9,
            ],
        ]);
    }
}

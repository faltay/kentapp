<?php

return [

    // ─── admin/impersonate ──────────────────────────────────────────────────────
    'impersonate' => [
        'login_as'      => 'Login As',
        'stop'          => 'Exit Impersonation',
        'banner'        => 'You are logged in as :name. (Admin: :admin)',
        'cannot_self'   => 'You cannot impersonate yourself.',
        'cannot_admin'  => 'You cannot impersonate a super admin.',
    ],

    // ─── admin/dashboard.php ──────────────────────────────────────────────────
    'dashboard' => [
        'recent_listings'      => 'Recent Listings',
        'recent_transactions'  => 'Recent Credit Transactions',
        'awaiting_approval'    => 'Awaiting approval',
        'awaiting_moderation'  => 'Awaiting moderation',
        'no_pending'           => 'None pending',
        'no_listings_yet'      => 'No listings yet.',
        'no_transactions_yet'  => 'No transactions yet.',
        'total_users'          => 'Total Users',
        'total_restaurants'    => 'Total Restaurants',
        'active_subscriptions' => 'Active Subscriptions',
        'total_revenue'        => 'Total Revenue',
        'paid_plans'           => 'Paid plans',

        'monthly_revenue'      => 'Monthly Revenue',
        'revenue'              => 'Revenue',

        'plan_distribution'    => 'Plan Distribution',

        'recent_payments'      => 'Recent Payments',
        'recent_restaurants'   => 'Recent Restaurants',

        'restaurant'           => 'Restaurant',
        'amount'               => 'Amount',
        'date'                 => 'Date',
        'plan'                 => 'Plan',

        'no_data'              => 'No data yet.',

        'active_subscriptions_label' => 'Active subscriptions',
        'subscription_rate'  => 'Subscription rate',
        'registered_users'   => 'Registered users',
        'total_collected'    => 'Total collected',
        'monthly'            => 'Monthly',
        'yearly'             => 'Yearly',
        'active_subs_chart'  => 'Active subscriptions',
        'popular_features'   => 'Popular Features',
        'usage_rate'         => 'Usage rate across restaurants',
        'qr_menu'            => 'QR Menu',
        'restaurants'        => 'restaurants',
        'table_management'   => 'Table Management',
        'tables_qr_codes'    => 'Tables & QR codes',
        'ai_menu'            => 'AI Menu',
        'auto_content'       => 'Automated content generation',
        'subscription_mgmt'  => 'Subscription Management',
        'total'              => 'Total',
    ],

    // ─── admin/users.php ──────────────────────────────────────────────────────
    'users' => [
        'title'                => 'Users',
        'create'               => 'Create User',
        'edit'                 => 'Edit User',
        'created_successfully' => 'User created successfully.',
        'updated_successfully' => 'User updated successfully.',
        'deleted_successfully' => 'User deleted successfully.',
        'creation_failed'      => 'Failed to create user.',
        'update_failed'        => 'Failed to update user.',
        'deletion_failed'      => 'Failed to delete user.',
        'confirm_delete'       => 'Are you sure you want to delete this user?',

        'suspended'            => 'Suspended',

        'form' => [
            'user_info'          => 'User Information',
            'name'               => 'Full Name',
            'email'              => 'Email Address',
            'password'           => 'Password',
            'password_hint'      => 'Leave blank to keep current password.',
            'role'               => 'Role',
            'type'               => 'Account Type',
            'type_land_owner'    => 'Land Owner',
            'type_contractor'    => 'Contractor',
            'type_agent'         => 'Real Estate Agent',
            'status'             => 'Status',
            'phone'              => 'Phone',
            'is_active'          => 'Active',
            'is_suspended'       => 'Suspend User',
            'is_suspended_hint'  => 'Suspended users cannot access the panel.',
        ],

        'roles' => [
            'super_admin'         => 'Super Admin',
            'verified_contractor' => 'Verified Contractor',
            'land_owner'          => 'Land Owner',
        ],

        'validation' => [
            'email_unique' => 'This email address is already in use.',
        ],
    ],

    // ─── admin/agents.php ─────────────────────────────────────────────────────
    'agents' => [
        'title'                => 'Real Estate Agents',
        'create'               => 'New Agent',
        'edit'                 => 'Edit Agent',
        'created_successfully' => 'Real estate agent created successfully.',
        'creation_failed'      => 'Failed to create real estate agent.',
        'updated_successfully' => 'Real estate agent updated successfully.',
        'update_failed'        => 'Failed to update real estate agent.',
        'deleted_successfully' => 'Real estate agent deleted successfully.',
        'deletion_failed'      => 'Failed to delete real estate agent.',
        'confirm_delete'       => 'Are you sure you want to delete this real estate agent?',
        'no_profile'           => 'No profile information found.',
        'credit_balance'       => 'Credit Balance',
        'recent_views'         => 'Recently Viewed Listings',

        'form' => [
            'company_info'             => 'Company Information',
            'company_name'             => 'Company Name',
            'authorized_name'          => 'Authorized Person',
            'company_phone'            => 'Company Phone',
            'company_email'            => 'Company Email',
            'company_address'          => 'Company Address',
            'working_neighborhoods'    => 'Working Areas',
            'no_areas'                 => 'No working areas added yet.',
            'area_placeholder'         => 'Type province, district or neighborhood...',
            'area_hint'                => 'Suggestions appear as you type. Press Enter to add a custom value.',
            'area_add_custom'          => 'Add',
            'area_no_results'          => 'No results found',
            'certificate_status'       => 'Certificate Status',
            'certificate_number'       => 'Certificate Number',
            'certificate_file'         => 'Authority Certificate',
            'certificate_drop'         => 'Drag & drop a file or click to select',
            'certificate_hint'         => 'PDF, JPG or PNG — max 10 MB',
        ],

        'certificate' => [
            'none'     => 'No Certificate',
            'pending'  => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ],
    ],

    // ─── admin/land_owners.php ────────────────────────────────────────────────
    'land_owners' => [
        'title'                => 'Land Owners',
        'create'               => 'New Land Owner',
        'edit'                 => 'Edit Land Owner',
        'created_successfully' => 'Land owner created successfully.',
        'creation_failed'      => 'Failed to create land owner.',
        'updated_successfully' => 'Land owner updated successfully.',
        'update_failed'        => 'Failed to update land owner.',
        'deleted_successfully' => 'Land owner deleted successfully.',
        'deletion_failed'      => 'Failed to delete land owner.',
        'confirm_delete'       => 'Are you sure you want to delete this land owner?',
        'listing_count'        => 'Listing',

        'form' => [
            'tc_number' => 'TC Identity No',
        ],
    ],

    // ─── admin/contractors.php ────────────────────────────────────────────────
    'contractors' => [
        'title'                => 'Contractors',
        'create'               => 'New Contractor',
        'edit'                 => 'Edit Contractor',
        'created_successfully' => 'Contractor created successfully.',
        'creation_failed'      => 'Failed to create contractor.',
        'updated_successfully' => 'Contractor updated successfully.',
        'update_failed'        => 'Failed to update contractor.',
        'deleted_successfully' => 'Contractor deleted successfully.',
        'deletion_failed'      => 'Failed to delete contractor.',
        'confirm_delete'       => 'Are you sure you want to delete this contractor?',
        'no_profile'           => 'No profile information found.',
        'credit_balance'       => 'Credit Balance',
        'recent_views'         => 'Recently Viewed Listings',

        'form' => [
            'company_info'        => 'Company Information',
            'company_name'        => 'Company Name',
            'authorized_name'     => 'Authorized Person',
            'company_phone'       => 'Company Phone',
            'company_email'       => 'Company Email',
            'company_address'          => 'Company Address',
            'working_neighborhoods'    => 'Working Areas',
            'no_areas'                 => 'No working areas added yet.',
            'area_placeholder'         => 'Type province, district or neighborhood...',
            'area_hint'                => 'Suggestions appear as you type. Press Enter to add a custom value.',
            'area_add_custom'          => 'Add',
            'area_no_results'          => 'No results found',
            'certificate_status'  => 'Certificate Status',
            'certificate_number'  => 'Certificate Number',
            'certificate_file'    => 'Authority Certificate',
            'certificate_drop'    => 'Drag & drop a file or click to select',
            'certificate_hint'    => 'PDF, JPG or PNG — max 10 MB',
        ],

        'certificate' => [
            'none'     => 'No Certificate',
            'pending'  => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ],
    ],

    // ─── admin/restaurants.php ────────────────────────────────────────────────
    'restaurants' => [
        'title'                => 'Restaurants',
        'create'               => 'New Restaurant',
        'show'                 => 'Restaurant Details',
        'edit'                 => 'Edit Restaurant',
        'created_successfully' => 'Restaurant created successfully.',
        'creation_failed'      => 'Failed to create restaurant.',
        'updated_successfully' => 'Restaurant updated successfully.',
        'update_failed'        => 'Failed to update restaurant.',
        'deleted_successfully' => 'Restaurant deleted successfully.',
        'deletion_failed'      => 'Failed to delete restaurant.',
        'confirm_delete'       => 'Are you sure you want to delete this restaurant? All data will be permanently deleted.',
        'suspended'            => 'Suspended',
        'no_plan'              => 'No Plan',

        'form' => [
            'owner'                   => 'Owner',
            'owner_section'              => 'Owner Account',
            'select_owner'               => 'Owner Account',
            'select_owner_placeholder'   => 'Search owner...',
            'owner_must_be_owner'        => 'Selected user must be an owner account.',
            'owner_name'                 => 'Full Name',
            'owner_email'                => 'Email Address',
            'owner_password'             => 'Password',
            'owner_password_confirm'     => 'Confirm Password',
            'restaurant_section'      => 'Restaurant Info',
            'contact_section'         => 'Contact & Location',
            'name'                    => 'Name',
            'description'             => 'Description',
            'email'                   => 'Restaurant Email',
            'phone'                   => 'Phone',
            'address'                 => 'Address',
            'city'                    => 'City',
            'country'                 => 'Country',
            'currency'                => 'Currency',
            'timezone'                => 'Timezone',
            'subscription_plan'       => 'Subscription Plan',
            'no_plan'                 => '— No Plan (Free) —',
            'is_active'               => 'Active',
            'is_suspended'            => 'Suspended',
            'is_suspended_hint'       => 'Suspended restaurants cannot be accessed by restaurant owners.',
            'languages'               => 'Menu Languages',
            'languages_hint'          => 'Languages available in the public menu. Select all languages the menu will be displayed in.',
            'languages_limit'         => 'Your plan allows a maximum of :max language(s).',
        ],

        'table' => [
            'restaurant' => 'Restaurant',
            'owner'      => 'Owner',
            'plan'       => 'Plan',
        ],
    ],

    // ─── admin/listings.php ───────────────────────────────────────────────────
    'listings' => [
        'title'   => 'Listings',
        'create'  => 'Create Listing',
        'edit'    => 'Edit Listing',
        'show'    => 'Listing Detail',
        'approve' => 'Approve',
        'reject'  => 'Reject',
        'passive' => 'Set Passive',
        'set_featured'    => 'Set Featured',
        'remove_featured' => 'Remove Featured',
        'featured'        => 'Featured',
        'owner_info'      => 'Listing Owner',
        'stats'           => 'Statistics',
        'total_views'     => 'Views',
        'total_reviews'   => 'Reviews',

        'type'               => 'Listing Type',
        'type_urban_renewal' => 'Urban Renewal',
        'type_land'          => 'Land',

        'status_pending'  => 'Pending',
        'status_active'   => 'Active',
        'status_rejected' => 'Rejected',
        'status_passive'  => 'Passive',
        'status_draft'    => 'Draft',

        'zoning_residential' => 'Residential',
        'zoning_commercial'  => 'Commercial',
        'zoning_mixed'       => 'Mixed',
        'zoning_unplanned'   => 'Unplanned',

        'agreement_kat_karsiligi'      => 'Revenue Share (Flat)',
        'agreement_para_karsiligi'     => 'Cash Purchase',
        'agreement_karma_para_kat'     => 'Mixed (Cash + Flat)',
        'agreement_hasilat_paylasimli' => 'Revenue Share',
        'agreement_yap_islet_devret'   => 'Build-Operate-Transfer',
        'agreement_kismi_satis_kat'    => 'Partial Sale + Flat',

        'created_successfully'   => 'Listing created successfully.',
        'updated_successfully'   => 'Listing updated successfully.',
        'approved_successfully'  => 'Listing approved.',
        'rejected_successfully'  => 'Listing rejected.',
        'passived_successfully'  => 'Listing set to passive.',
        'deleted_successfully'   => 'Listing deleted.',
        'featured_enabled'       => 'Listing set as featured.',
        'featured_disabled'      => 'Listing removed from featured.',
        'creation_failed'        => 'Listing creation failed.',
        'update_failed'          => 'Listing update failed.',
        'approval_failed'        => 'Listing approval failed.',
        'rejection_failed'       => 'Listing rejection failed.',
        'passive_failed'         => 'Listing passive failed.',
        'deletion_failed'        => 'Listing deletion failed.',
        'featured_failed'        => 'Featured status change failed.',

        'confirm_approve' => 'Are you sure you want to approve this listing?',
        'confirm_reject'  => 'Are you sure you want to reject this listing?',
        'confirm_passive' => 'Are you sure you want to set this listing as passive?',

        'table' => [
            'owner'    => 'Owner',
            'location' => 'Location',
            'type'     => 'Type',
        ],

        'form' => [
            'listing_info'   => 'Listing Information',
            'owner'          => 'Owner',
            'owner_placeholder' => 'Select a land owner or agent...',
            'type'           => 'Type',
            'province'       => 'Province',
            'district'       => 'District',
            'neighborhood'   => 'Neighborhood',
            'address'        => 'Address',
            'ada_no'          => 'Block No',
            'parcel_no'       => 'Parcel No',
            'pafta'           => 'Map Sheet',
            'gabari'          => 'Building Height',
            'agreement_model' => 'Agreement Model',
            'area_m2'        => 'Area (m²)',
            'floor_count'    => 'Floor Count',
            'zoning_status'  => 'Zoning Status',
            'taks'           => 'TAKS',
            'kaks'           => 'KAKS',
            'description'    => 'Description',
            'is_featured'    => 'Featured Listing',
            'is_featured_hint' => 'Highlighted on the contractor home page.',
            'view_count'     => 'View Count',
            'expires_at'     => 'Expires At',
            'documents'            => 'Title Deed Document',
            'documents_drop'       => 'Drag & drop PDF, JPG or PNG files here',
            'documents_hint'       => 'or click to browse — max 10 MB',
            'photos'               => 'Photos',
            'photos_drop'          => 'Drag & drop photos here',
            'photos_hint'          => 'or click to browse — JPG, PNG, WebP — max 5 MB',
            'photos_existing_hint' => 'Check photos you want to remove.',
            'add_more'             => 'Add Files',
            'select_district'      => 'Select province first',
            'select_neighborhood'  => 'Select district first',
        ],
    ],

    // ─── admin/contractor_certificates.php ────────────────────────────────────
    'contractor_certificates' => [
        'title'   => 'Authority Certificates',
        'approve' => 'Approve',
        'reject'  => 'Reject',
        'approve_title' => 'Approve Certificate',
        'certificate_number' => 'Certificate Number',
        'certificate_number_placeholder' => 'Enter certificate number...',

        'status_none'     => 'None',
        'status_pending'  => 'Pending',
        'status_approved' => 'Approved',
        'status_rejected' => 'Rejected',

        'approved_successfully' => 'Certificate approved.',
        'rejected_successfully' => 'Certificate rejected.',
        'approval_failed'       => 'Approval failed.',
        'rejection_failed'      => 'Rejection failed.',

        'confirm_reject' => 'Are you sure you want to reject this certificate?',

        'table' => [
            'contractor' => 'Contractor',
            'email'      => 'Email',
            'company'    => 'Company',
        ],
    ],

    // ─── admin/credit_packages.php ────────────────────────────────────────────
    'credit_packages' => [
        'title'  => 'Credit Packages',
        'create' => 'New Package',
        'edit'   => 'Edit Package',

        'created_successfully' => 'Credit package created.',
        'updated_successfully' => 'Credit package updated.',
        'deleted_successfully' => 'Credit package deleted.',
        'creation_failed'      => 'Credit package creation failed.',
        'update_failed'        => 'Credit package update failed.',
        'deletion_failed'      => 'Credit package deletion failed.',
        'confirm_delete'       => 'Are you sure you want to delete this credit package?',

        'table' => [
            'name'    => 'Package Name',
            'credits' => 'Credits',
            'price'   => 'Price',
        ],

        'form' => [
            'package_info' => 'Package Information',
            'name'         => 'Package Name',
            'name_placeholder' => 'e.g. 10 Credits, 50 Credits...',
            'credits'      => 'Credit Amount',
            'price'        => 'Price',
            'currency'     => 'Currency',
            'sort_order'   => 'Sort Order',
            'is_active'    => 'Active',
        ],
    ],

    // ─── admin/credit_transactions.php ────────────────────────────────────────
    'credit_transactions' => [
        'title'  => 'Credit Transactions',
        'assign' => 'Add Credits',
        'type'   => 'Transaction Type',

        'assigned_successfully' => 'Credits assigned successfully.',
        'assignment_failed'     => 'Failed to assign credits.',

        'type_purchase' => 'Purchase',
        'type_spend'    => 'Spend',
        'type_refund'   => 'Refund',

        'table' => [
            'contractor'    => 'User',
            'listing'       => 'Listing',
            'type'          => 'Type',
            'amount'        => 'Amount',
            'balance_after' => 'Balance After',
            'description'   => 'Description',
        ],

        'form' => [
            'user'                    => 'User',
            'user_placeholder'        => 'Search contractor or agent...',
            'user_no_results'         => 'No user found',
            'type'                    => 'Transaction Type',
            'amount'                  => 'Credit Amount',
            'current_balance'         => 'Current Balance',
            'description_placeholder' => 'Transaction description (optional)',
        ],

        'assign_btn' => 'Add Credits',
    ],

    // ─── admin/reviews.php ────────────────────────────────────────────────────
    'reviews' => [
        'title'   => 'Reviews',
        'approve' => 'Approve',
        'reject'  => 'Reject',

        'status_pending'  => 'Pending',
        'status_approved' => 'Approved',
        'status_rejected' => 'Rejected',

        'approved_successfully' => 'Review approved.',
        'rejected_successfully' => 'Review rejected.',
        'deleted_successfully'  => 'Review deleted.',
        'approval_failed'       => 'Approval failed.',
        'rejection_failed'      => 'Rejection failed.',
        'deletion_failed'       => 'Deletion failed.',

        'table' => [
            'reviewer' => 'Reviewer',
            'reviewed' => 'Reviewed',
            'rating'   => 'Rating',
            'comment'  => 'Comment',
        ],
    ],

    // ─── admin/plans.php ──────────────────────────────────────────────────────
    'plans' => [
        'title'                => 'Subscription Plans',
        'create'               => 'Create Plan',
        'edit'                 => 'Edit Plan',
        'created_successfully' => 'Plan created successfully.',
        'updated_successfully' => 'Plan updated successfully.',
        'deleted_successfully' => 'Plan deleted successfully.',
        'creation_failed'      => 'Failed to create plan.',
        'update_failed'        => 'Failed to update plan.',
        'deletion_failed'      => 'Failed to delete plan.',
        'confirm_delete'       => 'Are you sure you want to delete this plan?',
        'free'                 => 'Free',

        'form' => [
            'name'              => 'Plan Name',
            'slug'              => 'Slug',
            'slug_hint'         => 'URL-friendly identifier (e.g. free, standard, pro)',
            'description'       => 'Description',
            'currency'          => 'Currency',
            'price_monthly'     => 'Monthly',
            'price_yearly_short' => 'Yearly',
            'add_currency'      => 'Add Currency',
            'prices_hint'       => 'Set prices per currency. Leave empty for free plans.',
            'limits'            => 'Plan Limits',
            'max_restaurants' => 'Max Restaurants',
            'max_branches'    => 'Max Branches',
            'max_menu_items'  => 'Max Menu Items',
            'max_tables'      => 'Max Tables',
            'max_languages'   => 'Max Languages',
            'unlimited_hint'  => 'Enter -1 for unlimited.',
            'features'        => 'Features',
            'features_hint'   => 'List of features shown on the pricing page.',
            'add_feature'        => 'Add Feature',
            'feature_placeholder' => 'e.g. QR Code Generation',
            'is_active'       => 'Active',
            'is_featured'     => 'Featured (highlight on pricing page)',
            'sort_order'      => 'Sort Order',
        ],

        'table' => [
            'plan'        => 'Plan',
            'price'       => 'Price',
            'limits'      => 'Limits',
            'restaurants' => 'Restaurants',
            'branches'    => 'Branches',
            'menu_items'  => 'Menu Items',
            'tables'      => 'Tables',
            'languages'   => 'Languages',
            'unlimited'   => 'Unlimited',
            'month'       => 'mo',
            'year'        => 'yr',
        ],

        'validation' => [
            'slug_unique' => 'This slug is already in use.',
        ],
    ],

    // ─── admin/blog_categories.php ──────────────────────────────────────────────
    'blog_categories' => [
        'title'    => 'Blog Categories',
        'subtitle' => 'Manage blog post categories.',

        'create'     => 'New Category',
        'edit'       => 'Edit Category',
        'delete'     => 'Delete Category',
        'activate'   => 'Activate',
        'deactivate' => 'Deactivate',

        'form' => [
            'info_section'     => 'Category Info',
            'slug_section'     => 'SEO & URL',
            'settings_section' => 'Settings',
            'name'             => 'Name',
            'description'      => 'Description',
            'slug'                  => 'Slug (URL)',
            'slug_hint'             => 'Leave empty to auto-generate from name.',
            'meta_description'      => 'Meta Description',
            'meta_description_hint' => 'SEO description for search engines (max 160 chars).',
            'sort_order'            => 'Sort Order',
            'is_active'             => 'Active',
        ],

        'table' => [
            'id'           => '#',
            'name'         => 'Name',
            'translations' => 'Languages',
            'slug'         => 'Slug',
            'posts_count'  => 'Posts',
            'sort_order'   => 'Order',
            'status'       => 'Status',
            'actions'      => 'Actions',
        ],

        'created_successfully'     => 'Category created successfully.',
        'updated_successfully'     => 'Category updated successfully.',
        'deleted_successfully'     => 'Category deleted successfully.',
        'activated_successfully'   => 'Category activated.',
        'deactivated_successfully' => 'Category deactivated.',
        'reordered_successfully'   => 'Categories reordered.',
        'creation_failed'          => 'Failed to create category.',
        'update_failed'            => 'Failed to update category.',
        'deletion_failed'          => 'Failed to delete category.',
        'reorder_failed'           => 'Failed to reorder categories.',
        'confirm_delete'           => 'Are you sure you want to delete this category? Posts in this category will become uncategorized.',
    ],

    // ─── admin/posts.php ──────────────────────────────────────────────────────
    'posts' => [
        'title'    => 'Blog Posts',
        'subtitle' => 'Manage blog posts and articles.',

        'create'   => 'New Post',
        'edit'     => 'Edit Post',
        'delete'   => 'Delete Post',
        'publish'  => 'Publish',
        'unpublish' => 'Unpublish',

        'status_published' => 'Published',
        'status_draft'     => 'Draft',

        'form' => [
            'content_section' => 'Post Content',
            'seo_section'     => 'SEO & URL',
            'publish_section' => 'Publish Settings',
            'title'           => 'Title',
            'title_hint'      => 'Enter the post title for each language.',
            'slug'            => 'Slug (URL)',
            'slug_hint'       => 'Leave empty to auto-generate from title.',
            'excerpt'         => 'Excerpt',
            'excerpt_hint'    => 'Short summary shown in listings (max 500 chars).',
            'content'         => 'Content',
            'category'        => 'Category',
            'no_category'     => '— No Category —',
            'meta_description'      => 'Meta Description',
            'meta_description_hint' => 'SEO description for search engines (max 160 chars).',
            'image_section'         => 'Featured Image',
            'image_drop'            => 'Drop image here or click to upload',
            'image_hint'            => 'JPEG, PNG or WebP. Max 2 MB.',
            'is_published'          => 'Published',
            'published_at'          => 'Publish Date',
        ],

        'table' => [
            'id'           => '#',
            'title'        => 'Title',
            'category'     => 'Category',
            'author'       => 'Author',
            'status'       => 'Status',
            'published_at'  => 'Published',
            'actions'       => 'Actions',
            'translations'  => 'Translations',
        ],

        'created_successfully'     => 'Post created successfully.',
        'updated_successfully'     => 'Post updated successfully.',
        'deleted_successfully'     => 'Post deleted successfully.',
        'published_successfully'   => 'Post published.',
        'unpublished_successfully' => 'Post set to draft.',
        'creation_failed'          => 'Failed to create post.',
        'update_failed'            => 'Failed to update post.',
        'deletion_failed'          => 'Failed to delete post.',
        'confirm_delete'           => 'Are you sure you want to delete this post?',
        'no_posts'                 => 'No posts found.',
    ],

    // ─── admin/pages.php ──────────────────────────────────────────────────────
    'pages' => [
        'title'    => 'Pages',
        'subtitle' => 'Manage static pages (About, Contact, Privacy Policy, etc.).',

        'create' => 'New Page',
        'edit'   => 'Edit Page',
        'delete' => 'Delete Page',

        'status_published' => 'Published',
        'status_draft'     => 'Draft',

        'form' => [
            'content_section'       => 'Page Content',
            'seo_section'           => 'SEO & URL',
            'settings_section'      => 'Settings',
            'title'                 => 'Title',
            'title_hint'            => 'Enter the page title for each language.',
            'slug'                  => 'Slug (URL)',
            'slug_hint'             => 'Leave empty to auto-generate from title.',
            'content'               => 'Content',
            'meta_description'      => 'Meta Description',
            'meta_description_hint' => 'SEO description for search engines (max 160 chars).',
            'is_published'          => 'Published',
            'is_homepage'           => 'Set as Homepage',
            'sort_order'            => 'Sort Order',
        ],

        'table' => [
            'id'           => '#',
            'title'        => 'Title',
            'translations' => 'Languages',
            'status'       => 'Status',
            'sort_order'   => 'Order',
            'actions'      => 'Actions',
        ],

        'created_successfully' => 'Page created successfully.',
        'updated_successfully' => 'Page updated successfully.',
        'deleted_successfully' => 'Page deleted successfully.',
        'creation_failed'      => 'Failed to create page.',
        'update_failed'        => 'Failed to update page.',
        'deletion_failed'      => 'Failed to delete page.',
        'confirm_delete'       => 'Are you sure you want to delete this page?',
        'no_pages'             => 'No pages found.',
    ],

    // ─── admin/languages.php ────────────────────────────────────────────────────
    'languages' => [
        'title'    => 'Languages',
        'subtitle' => 'Manage system languages.',

        'create' => 'New Language',
        'edit'   => 'Edit Language',
        'delete' => 'Delete Language',

        'default' => 'Default',

        'form' => [
            'info_section'     => 'Language Info',
            'settings_section' => 'Settings',
            'code'             => 'Code',
            'code_hint'        => 'ISO 639-1 two-letter code (e.g. en, tr, de).',
            'name'             => 'Name',
            'name_hint'        => 'Language name in English (e.g. English, Turkish).',
            'native'           => 'Native Name',
            'native_hint'      => 'Language name in its own language (e.g. Türkçe).',
            'flag'             => 'Flag Emoji',
            'direction'        => 'Text Direction',
            'direction_ltr'    => 'Left to Right (LTR)',
            'direction_rtl'    => 'Right to Left (RTL)',
            'sort_order'       => 'Sort Order',
            'is_active'        => 'Active',
            'is_default'       => 'Default Language',
            'is_default_hint'  => 'The default language is used as the primary fallback.',
        ],

        'table' => [
            'code'      => 'Code',
            'name'      => 'Language',
            'direction' => 'Direction',
            'default'   => 'Default',
            'status'    => 'Status',
            'actions'   => 'Actions',
        ],

        'created_successfully'     => 'Language created successfully.',
        'updated_successfully'     => 'Language updated successfully.',
        'deleted_successfully'     => 'Language deleted successfully.',
        'activated_successfully'   => 'Language activated.',
        'deactivated_successfully' => 'Language deactivated.',
        'creation_failed'          => 'Failed to create language.',
        'update_failed'            => 'Failed to update language.',
        'deletion_failed'          => 'Failed to delete language.',
        'confirm_delete'           => 'Are you sure you want to delete this language? Existing translations will NOT be removed.',
    ],

    // ─── admin/branches.php ─────────────────────────────────────────────────────
    'branches' => [
        'title'                => 'Branches',
        'create'               => 'New Branch',
        'edit'                 => 'Edit Branch',
        'created_successfully' => 'Branch created successfully.',
        'updated_successfully' => 'Branch updated successfully.',
        'deleted_successfully' => 'Branch deleted successfully.',
        'creation_failed'      => 'Failed to create branch.',
        'update_failed'        => 'Failed to update branch.',
        'deletion_failed'      => 'Failed to delete branch.',
        'confirm_delete'       => 'Are you sure you want to delete this branch?',
        'main'                 => 'Main',

        'form' => [
            'branch_section'               => 'Branch Info',
            'contact_section'              => 'Contact & Location',
            'name'                         => 'Branch Name',
            'email'                        => 'Email',
            'phone'                        => 'Phone',
            'address'                      => 'Address',
            'city'                         => 'City',
            'sort_order'                   => 'Sort Order',
            'is_active'                    => 'Active',
            'is_main'                      => 'Main Branch',
            'is_main_hint'                 => 'Only one branch per restaurant can be the main branch.',
            'restaurant'                   => 'Restaurant',
            'select_restaurant'            => 'Restaurant',
            'select_restaurant_placeholder' => 'Search restaurant...',
            'select_restaurant_first'       => 'Please select a restaurant first to see the available languages.',
            'languages'                     => 'Menu Languages',
            'languages_placeholder'         => 'Select languages',
            'languages_hint'                => 'First selected language is required for branch name.',
            'select_language_first'         => 'Please select at least one language.',
        ],

        'table' => [
            'branch'     => 'Branch',
            'restaurant' => 'Restaurant',
            'location'   => 'Location',
        ],
    ],

    // ─── admin/settings.php ─────────────────────────────────────────────────────
    'settings' => [
        'title'                => 'Settings',
        'updated_successfully' => 'Settings updated successfully.',
        'update_failed'        => 'Failed to update settings.',

        'form' => [
            'general_section'  => 'General Information',
            'contact_section'  => 'Contact Information',
            'social_section'   => 'Social Media',
            'site_name'        => 'Site Name',
            'site_description' => 'Site Description',
            'meta_title'       => 'Meta Title',
            'meta_description' => 'Meta Description',
            'contact_email'    => 'Contact Email',
            'contact_phone'    => 'Contact Phone',
            'address'          => 'Address',
            'facebook'         => 'Facebook',
            'instagram'        => 'Instagram',
            'twitter'          => 'X (Twitter)',
            'youtube'          => 'YouTube',
            'tiktok'           => 'TikTok',
            'logo'             => 'Logo',
            'logo_hint'        => 'JPEG, PNG or WebP. Max 2 MB.',
            'favicon'          => 'Favicon',
            'favicon_hint'     => 'PNG or ICO. Max 512 KB.',
        ],
    ],

    // ─── admin/subscriptions.php ──────────────────────────────────────────────
    'subscriptions' => [
        'title'    => 'Subscriptions',
        'subtitle' => 'All user subscriptions.',

        'status' => [
            'active'    => 'Active',
            'trialing'  => 'Trialing',
            'cancelled' => 'Cancelled',
            'expired'   => 'Expired',
            'past_due'  => 'Past Due',
        ],

        'cycle' => [
            'monthly' => 'Monthly',
            'yearly'  => 'Yearly',
        ],

        'filters' => [
            'title'        => 'Filters',
            'plan'         => 'Plan',
            'status'       => 'Status',
            'cycle'        => 'Cycle',
            'date_from'    => 'Date From',
            'date_to'      => 'Date To',
            'all_plans'    => 'All Plans',
            'all_statuses' => 'All Statuses',
            'all_cycles'   => 'All Cycles',
        ],

        'table' => [
            'user'       => 'User',
            'plan'       => 'Plan',
            'status'     => 'Status',
            'cycle'      => 'Cycle',
            'amount'     => 'Amount',
            'started_at' => 'Started',
            'ends_at'    => 'Ends At',
        ],
    ],

    // ─── admin/payments.php ───────────────────────────────────────────────────
    'payments' => [
        'title'    => 'Payments',

        'status' => [
            'pending'            => 'Pending',
            'succeeded'          => 'Succeeded',
            'failed'             => 'Failed',
            'refunded'           => 'Refunded',
            'partially_refunded' => 'Partially Refunded',
        ],

        'provider' => [
            'iyzico'        => 'İyzico',
            'bank_transfer' => 'Bank Transfer',
            'google_pay'    => 'Google Pay',
            'apple_pay'     => 'Apple Pay',
        ],

        'filters' => [
            'title'          => 'Filters',
            'provider'       => 'Payment Method',
            'status'         => 'Status',
            'currency'       => 'Currency',
            'date_from'      => 'Date From',
            'date_to'        => 'Date To',
            'all_providers'  => 'All Methods',
            'all_statuses'   => 'All Statuses',
            'all_currencies' => 'All Currencies',
        ],

        'table' => [
            'user'     => 'User',
            'package'  => 'Package',
            'credits'  => 'Credits',
            'provider' => 'Payment Method',
            'amount'   => 'Amount',
            'status'   => 'Status',
            'paid_at'  => 'Paid At',
        ],
    ],

];

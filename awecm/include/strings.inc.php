<?php 
if(!defined('__URBAN_OFFICE__')) exit;

$image_size_dom = [
  'thumbnails' => ['555x430'],
  'crop'       => ['555x555'],
];

$status_dom = [
  'draft' => 'Draft',
  'sent'  => 'Sent'
];

$customer_status_dom = [
  'active' => 'Active',
  'inactive'  => 'Inactive'
];

$content_status_dom = [
  'published' => 'Published',
  'draft'     => 'Draft'
];

$template_dom = [
  "home_page_template"     => "Home Page",
  "property_page_template" => "Property Details Page",
  "property_list_template" => "Location",
  "press_page_template"    => "Press Page",
  "press_detail_template"  => "Press Details Page",
  "our_team_page_template"    => "Our Team Page",
  "our_team_detail_template"  => "Our Team Details Page",
  "application_form_template" => "Member Application",
  "blog_page_template"        => "Blog roll",
  "blog_detail_template"      => "Blog post",
];

$template_action_dom = [
  "home_page_template"     => "Home",
  "property_page_template" => "Property",
  "property_list_template" => "PropertyList",
  "press_page_template"    => "Press",
  "press_detail_template"  => "PressDetail",
  "blog_page_template"     => "Blog",
  "blog_detail_template"   => "BlogDetail",
  "our_team_page_template"    => "OurTeam",
  "our_team_detail_template"  => "OurTeamDetail",
  "application_form_template"  => "MemberForm",
];

$short_code_dom = [
  '{BASE_IMAGE_PATH}'   => 'get_media_path',
  '{SITE_URL}'          => 'get_site_url',
  '{CONTACTUS_FORM}'    => 'get_contact_us_form',
  '{PROPERTY_SLIDER}'   => 'property_slider',
  '{SMART_AMENITIES}'   => 'smart_amenities_slider',
  '{FEATURED_PROPERTY}' => 'get_featured_properties',
  '{FOOTER_LOCATION_SECTION}' => 'footer_location_section',
  '{FAQ_SECTION}'             => 'load_faq',
];

$settings_dom = [
  'site_name',
  'site_email',
  'header_script',
  'footer_script',
  // 'email_settings'
];

# Page settings dom
$page_settings_dom = array(
  'edge_profile'   => array(
    "label"  => 'Edge Profile Link',
   ),
);

# Email settings dom
$email_settings_dom = array(
  'send_email_from'   => array(
    "label"       => 'From Email',
    "placeholder" => 'From Email',
    "type"        => "text",
   ),
  'reply_to' => array(
    "label"       => 'Reply To',
    "placeholder" => 'Reply to Email',
    "type"        => "email",
   ),
  'bcc_email' => array(
    "label"       => 'BCC Email',
    "placeholder" => 'BCC Email',
    "type"        => "text",
   ),
);

$redirection_type =  [
  '301' => '301',
  '503' => '503'
];

$default_image_path = [
  "default_200x200" => $site_url."static/front/images/no-img-available-200x200.jpg",
  "default_265x265" => $site_url."static/front/images/no-img-available-265x265.jpg",
  "default_312x170" => $site_url."static/front/images/no-img-available-312x170.jpg",
  "default_340x280" => $site_url."static/front/images/no-img-available-340x280.jpg",
  "default_360x279" => $site_url."static/front/images/no-img-available-360x279.jpg",
  "default_375x200" => $site_url."static/front/images/no-img-available-375x200.jpg",
  "default_375x350" => $site_url."static/front/images/no-img-available-375x350.jpg",
  "default_555x265" => $site_url."static/front/images/no-img-available-555x265.jpg",
  "default_555x415" => $site_url."static/front/images/no-img-available-555x415.jpg",
  "default_555x430" => $site_url."static/front/images/no-img-available-555x430.jpg",
  "default_555x555" => $site_url."static/front/images/no-img-available-555x555.jpg",
  "default_795x400" => $site_url."static/front/images/no-img-available-795x400.jpg",
  "default_375x250" => $site_url."static/front/images/no-img-available-375x250.jpg",
  "default_750x430" => $site_url."static/front/images/no-img-available-750x430.jpg",
  "default_70x45"   => $site_url."static/front/images/no-img-available-70x45.jpg",
  "default_185x185" => $site_url."static/front/images/no-img-available-185x185.jpg",
];

$msg_dom = array(
  "add"      => "Added successfully",
  "update"   => "Updated successfully",
);

$enquiry_dom = [
  'contact_us'    => 'Contact Us',
  'reserve_space' => 'Reserve A Space'
];

$media_post_type_dom = array(
  'page'     => "Page",
  'property' => "Property",
);

$location_dom = array(
  "houston"     => "Houston",
  "san_antonio" => "San Antonio",
  "austin" => "Austin",
);

$amenities_dom = array(
  'within_walking_distance' => array(
    "title"      => "Restaurants, Bars, and Retail Within Walking Distance",
    "image_name" => "restaurant-bar.svg",
    "width"      => "40",
    "height"     => "62",
    "show_on_page_section" => 1
  ),
  'high_speed_internet' => array(
    "title"      => "High-Speed <br>Internet",
    "image_name" => "wifi.svg",
    "width"      => "56",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  'conference_room' => array(
    "title"      => "Conference <br>Rooms",
    "image_name" => "Conference-hall.svg",
    "width"      => "58",
    "height"     => "43",
    "show_on_page_section" => 0
  ),
  'conference_rooms' => array(
    "title"      => "Conference <br>Rooms",
    "image_name" => "Conference-hall.svg",
    "width"      => "58",
    "height"     => "43",
    "show_on_page_section" => 1
  ),
  'beverages' => array(
    "title"      => "Coffee, Tea, and Filtered Water",
    "image_name" => "coffee.svg",
    "width"      => "56",
    "height"     => "56",
    "show_on_page_section" => 1
  ),
  'seasonal_snacks' => array(
    "title"      => "Seasonal <br>Snacks",
    "image_name" => "seasonal-snacks.svg",
    "width"      => "63",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  'casual_common_lounge_space' => array(
    "title"      => "Casual Common Lounge Space",
    "image_name" => "lounge.svg",
    "width"      => "74",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  'printer_scanning' => array(
    "title"      => "Printer With Scanning And Copying Capabilities Available",
    "image_name" => "printer.svg",
    "width"      => "60",
    "height"     => "59",
    "show_on_page_section" => 1
  ),
  'mother_room' => array(
    "title"      => "Mother’s <br>Room",
    "image_name" => "mothers-room.svg",
    "width"      => "66",
    "height"     => "66",
    "show_on_page_section" => 1
  ),
  'kitchen' => array(
    "title"      => "Kitchen",
    "image_name" => "kitchen.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  'some_furnished_offices' => array(
    "title"      => "Option of Furnished <br>Offices",
    "image_name" => "furnished-office.svg",
    "width"      => "62",
    "height"     => "56",
    "show_on_page_section" => 1
  ),
  'phone_booths' => array(
    "title"      => "Phone <br>Booth",
    "image_name" => "phone-booth.svg",
    "width"      => "40",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  'notary_public' => array(
    "title"      => "Notary <br>Public",
    "image_name" => "notary-public.svg",
    "width"      => "50",
    "height"     => "72",
    "show_on_page_section" => 1
  ),
  'virtual_office' => array(
    "title"      => "Virtual <br>Office",
    "image_name" => "virtual-office.svg",
    "width"      => "62",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  'outdoor_workspace' => array(
    "title"      => "Outdoor <br>Workspace",
    "image_name" => "outdoor-workspace.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  'hassel-free-parking' => array(
    "title"      => "Convenient and Hassle-Free Parking",
    "image_name" => "parking.svg",
    "width"      => "58",
    "height"     => "49",
    "show_on_page_section" => 1
  ),
  'all_time_access' => array(
    "title"      => "24/7 Access",
    "image_name" => "access.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  'mailing_address' => array(
    "title"      => "Mailing <br>Address",
    "image_name" => "mail-address.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ),  
  'desk' => array(
    "title"      => "Hot Desks",
    "image_name" => "hot_desk.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ), 
  'dedicated-desk' => array(
    "title"      => "Dedicated Desks",
    "image_name" => "dedicated-desk.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
//     'notary-public' => array(
//     "title"      => "Notary Public",
//     "image_name" => "notary-public.svg",
//     "width"      => "58",
//     "height"     => "58",
//     "show_on_page_section" => 1
//   ),
    'huddle_rooms' => array(
    "title"      => "Huddle Rooms",
    "image_name" => "huddle-room.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  
  'huddle_room' => array(
    "title"      => "Huddle Room",
    "image_name" => "huddle-room.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  
  
    'phone-booths' => array(
    "title"      => "Phone Booths",
    "image_name" => "phone-booths.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 0
  ),  
  'podcast-room' => array(
    "title"      => "Podcast Room",
    "image_name" => "podcast-room.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  'library' => array(
    "title"      => "Library",
    "image_name" => "library.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ), 
  'break_room' => array(
    "title"      => "Break Room",
    "image_name" => "kitchen.svg",
    "width"      => "58",
    "height"     => "58",
    "show_on_page_section" => 1
  ),
  
);

$calendly_loaction_link = array(
  'houston' => array(
    'calendly_url' => "https://calendly.com/katherine-226/30min",
    "name"         => "Houston"
  ),
  'san_antonio' => array(
    'calendly_url' => 'https://calendly.com/sanantonio_tx/30min',//'https://calendly.com/andrea-mcpartlin/30min', //'https://calendly.com/isaac-gutierrez/30-minute-meeting',
    "name"         => "San Antonio"
  ),
  'austin' => array(
    'calendly_url' => 'https://calendly.com/isaac-gutierrez/30-minute-meeting',
    "name"         => "Austin"
  )
);

// Membership Form

$form_section = [];
$form_section[] = [
   'section_title' => 'Applicant',
   'fields' => [
       "suiteno" => "Suite No",
       "location" => "Urban Office Location",
       "duration_term" => "Desired Term of Membership (Months)",
       "start_date" => "Desired Start Date",
       "legalname" => "Legal Name",
       "dba" => "DBA",
       "business" => "Business Classification",
       "taxpayerid" => "Tax Payer ID",
       "state" => "State",
       "year" => "Year",
       "businesstype" => "Type of Business",
       "peoplenumber" => "Number of People",
       "contactperson" => "Contact Person",
       "phonenumber" => "Phone Number",
       "email" => "Email",
       "is_primary_billing" => "Billing is Primary Contact",
       "billing_contactperson" => "Billing Contact Person",
       "billing_phonenumber" => "Billing Phone Number",
       "billing_email" => "Billing Email",
       "homeaddress" => "Home Address",
       "home_street" => "Street",
       "home_city" => "City",
       "home_state" => "State",
       "home_zip" => "Zip",
       "hearabout" => "Heard About Urban Office",
       "hearabout_other" => "Other referral source",
       "need_furniture" => "Intend to rent furniture",
       "requested_furniture" => "Furniture items requested",

       
   ]
];


$form_section[] = [
   'section_title' => 'Has Applicant Ever',
   'fields' => [
      "filed_for_Bankruptcy" => "Filed for Bankruptcy?",
      "been_convicted_of_a_crime" => "Been convicted of a crime?",
      "registered_sex_offender" => "Is any occupant a registered sex offender?",
      "additional_information" => "Additional Information to be considered?",
      "has_applicant_ever_details" => "Additional details",      
   ]
];

$form_section[] = [
   'section_title' => 'Business Rental History',
   'fields' => [
      "presentofficestreet" => "Present Office Address",
      "presentofficecity" => "City",
      "presentofficestate" => "State",
      "presentofficezip" => "Zip",
      "present_office_ownership" => "Owned/Rented",
      "present_office_type" => "Present Office is",
      "present_office_other" => "if Other",
      "monthlyrentmortgage" => "Monthly Rent/Mortgage",
      "occupiedfrom" => "Occupied From",
      "occupiedto" => "To",
      "reasonforleaving" => "Reason for leaving",
      "loanservicer" => "Landlord/Loan Servicer",
      "rentalaccount" => "Mortgage/Rental Account",
      "contactemail" => "Contact Email"
   ]
];

$form_section[] = [
   'section_title' => 'Prior Office Address',
   'fields' => [
      "priorofficestreet" => "Street",
       "priorofficecity" => "City",
       "priorofficestate" => "State",
       "priorofficezip" => "Zip",
       "prior_office_type" => "Owned/Rented",
       "monthlyrentmortgagetwo" => "Monthly Rent/Mortgage",
       "business_rental_history_occupiedfrom" => "Occupied From",
       "business_rental_history_occupiedto" => "To",
       "business_rental_history_reasonforleaving" => "Reason for leaving",
       "business_rental_history_loanservicer" => "Landlord/Loan Service",
       "business_rental_history_rentalaccount" => "Mortgage/Rental Account",
       "business_rental_history_contactemail" => "Contact Email",
   ]
];


$form_section[] = [
   'section_title' => 'Guarantors',
   'fields' => [
       "guarantorsname" => "First Guarantor",
       "guarantorstitle" => "Title",
       "guarantorsdob" => "DOB",
       "guarantorsemail" => "Email",
       "homestreet" => "Home Address",
       "homecity" => "City",
       "homestate" => "State",
       "homezip" => "Zip ",
       "secondguarantorsname" => "Second Guarantor",
       "secondguarantorstitle" => "Title",
       "secondguarantorsdob" => "DOB",
       "secondguarantorsemail" => "Email",
       "homestreettwo" => "Home Address",
       "homecitytwo" => "City",
       "homestatetwo" => "State",
       "homeziptwo" => "Zip"
   ]
];

$form_section[] = [
   'section_title' => 'Certification',
   'fields' => [
       "cert_printed_name" => "Printed Name",
       "cert_title" => "Title",
       "cert_date" => "Date"
   ]
];


$form_valid_fields = ['suiteno' => 'Suite No'
, 'duration_term' => 'Duration Term'
, 'start_date' => 'Start Date'
, 'location' => 'Location'
, 'businesstype' => 'Business Type'
, 'peoplenumber' => 'People Number'
, 'legalname' => 'Legal Name'
, 'contactperson' => 'Contact Person'
, 'email' => 'Email'
, 'homeaddress' => 'Home Address'
, 'home_street' => 'Home Street'
, 'home_city' => 'Home City'
, 'home_state' => 'Home State'
, 'home_zip' => 'Home Zip'
, 'hearabout' => 'Heard About Urban Office'
, 'filed_for_bankruptcy' => 'Filed for Bankruptcy?'
, 'convictiontype' => 'Conviction Type'
, 'sexoffender' => 'Sex offender'
, 'additionalinformation' => 'Additional Information'
, 'guarantorsname' => 'Second Guarantor'
, 'guarantorstitle' => 'Guarantors Title'
, 'guarantorsdob' => 'Guarantors Dob'
, 'guarantorsemail' => 'guarantorsemail'
, 'homestreet' => 'Home Street'
, 'homecity' => 'Home City'
, 'homestate' => 'Home State'
, 'homezip' => 'Home Zip'
, 'cert_printed_name' => 'Printed Name'
, 'cert_title' => 'Title'
, 'cert_date' => 'Cert Date'
];

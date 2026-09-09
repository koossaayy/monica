<template>
  <div class="mb-16">
    <!-- title + cta -->
    <div class="mb-3 mt-8 items-center justify-between sm:mt-0 sm:flex">
      <h3 class="mb-4 flex font-semibold sm:mb-0">
        <span class="me-1"> 🗓 </span>
        <span class="me-2">
          {{ $t('Timezone') }}
        </span>

        <help :url="$page.props.help_links.settings_preferences_timezone" :top="'5px'" />
      </h3>
      <pretty-button v-if="!editMode" :text="$t('Edit')" @click="enableEditMode" />
    </div>

    <!-- help text -->
    <div class="mb-6 flex rounded-xs border bg-slate-50 px-3 py-2 text-sm dark:border-gray-700 dark:bg-slate-900">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6 pe-2"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>

      <div>
        <p>{{ $t('Regardless of where you are located in the world, have dates displayed in your own timezone.') }}</p>
      </div>
    </div>

    <!-- normal mode -->
    <div v-if="!editMode" class="mb-6 rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
      <p class="px-5 py-2">
        <span class="mb-2 block">{{ $t('Current timezone:') }}</span>
        <span class="mb-2 block rounded-xs bg-slate-100 px-5 py-2 text-sm dark:bg-slate-900">{{ localTimezone }}</span>
      </p>
    </div>

    <!-- edit mode -->
    <form
      v-if="editMode"
      class="mb-6 rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900"
      @submit.prevent="submit()">
      <div class="border-b border-gray-200 px-5 py-2 dark:border-gray-700">
        <errors :errors="form.errors" />

        <select
          v-model="form.timezone"
          name="timezone"
          class="rounded-md border-gray-300 bg-white px-3 py-2 pe-5 ps-2 shadow-xs focus:border-indigo-300 focus:outline-hidden focus:ring-3 focus:ring-indigo-200/50 ltr:bg-[right_3px_center] rtl:bg-[left_3px_center] dark:bg-gray-900 sm:text-sm">
          <optgroup :label="$t('General')">
            <option value="GMT">{{ $t('GMT timezone') }}</option>
            <option value="UTC">{{ $t('UTC timezone') }}</option>
          </optgroup>
          <optgroup :label="$t('America')">
            <option value="America/Adak">{{ $t('(GMT/UTC - 10:00) Adak') }}</option>
            <option value="America/Anchorage">{{ $t('(GMT/UTC - 09:00) Anchorage') }}</option>
            <option value="America/Anguilla">{{ $t('(GMT/UTC - 04:00) Anguilla') }}</option>
            <option value="America/Antigua">{{ $t('(GMT/UTC - 04:00) Antigua') }}</option>
            <option value="America/Araguaina">{{ $t('(GMT/UTC - 03:00) Araguaina') }}</option>
            <option value="America/Argentina/Buenos_Aires">{{ $t('(GMT/UTC - 03:00) Argentina/Buenos Aires') }}</option>
            <option value="America/Argentina/Catamarca">{{ $t('(GMT/UTC - 03:00) Argentina/Catamarca') }}</option>
            <option value="America/Argentina/Cordoba">{{ $t('(GMT/UTC - 03:00) Argentina/Cordoba') }}</option>
            <option value="America/Argentina/Jujuy">{{ $t('(GMT/UTC - 03:00) Argentina/Jujuy') }}</option>
            <option value="America/Argentina/La_Rioja">{{ $t('(GMT/UTC - 03:00) Argentina/La Rioja') }}</option>
            <option value="America/Argentina/Mendoza">{{ $t('(GMT/UTC - 03:00) Argentina/Mendoza') }}</option>
            <option value="America/Argentina/Rio_Gallegos">{{ $t('(GMT/UTC - 03:00) Argentina/Rio Gallegos') }}</option>
            <option value="America/Argentina/Salta">{{ $t('(GMT/UTC - 03:00) Argentina/Salta') }}</option>
            <option value="America/Argentina/San_Juan">{{ $t('(GMT/UTC - 03:00) Argentina/San Juan') }}</option>
            <option value="America/Argentina/San_Luis">{{ $t('(GMT/UTC - 03:00) Argentina/San Luis') }}</option>
            <option value="America/Argentina/Tucuman">{{ $t('(GMT/UTC - 03:00) Argentina/Tucuman') }}</option>
            <option value="America/Argentina/Ushuaia">{{ $t('(GMT/UTC - 03:00) Argentina/Ushuaia') }}</option>
            <option value="America/Aruba">{{ $t('(GMT/UTC - 04:00) Aruba') }}</option>
            <option value="America/Asuncion">{{ $t('(GMT/UTC - 03:00) Asuncion') }}</option>
            <option value="America/Atikokan">{{ $t('(GMT/UTC - 05:00) Atikokan') }}</option>
            <option value="America/Bahia">{{ $t('(GMT/UTC - 03:00) Bahia') }}</option>
            <option value="America/Bahia_Banderas">{{ $t('(GMT/UTC - 06:00) Bahia Banderas') }}</option>
            <option value="America/Barbados">{{ $t('(GMT/UTC - 04:00) Barbados') }}</option>
            <option value="America/Belem">{{ $t('(GMT/UTC - 03:00) Belem') }}</option>
            <option value="America/Belize">{{ $t('(GMT/UTC - 06:00) Belize') }}</option>
            <option value="America/Blanc-Sablon">{{ $t('(GMT/UTC - 04:00) Blanc-Sablon') }}</option>
            <option value="America/Boa_Vista">{{ $t('(GMT/UTC - 04:00) Boa Vista') }}</option>
            <option value="America/Bogota">{{ $t('(GMT/UTC - 05:00) Bogota') }}</option>
            <option value="America/Boise">{{ $t('(GMT/UTC - 07:00) Boise') }}</option>
            <option value="America/Cambridge_Bay">{{ $t('(GMT/UTC - 07:00) Cambridge Bay') }}</option>
            <option value="America/Campo_Grande">{{ $t('(GMT/UTC - 03:00) Campo Grande') }}</option>
            <option value="America/Cancun">{{ $t('(GMT/UTC - 05:00) Cancun') }}</option>
            <option value="America/Caracas">{{ $t('(GMT/UTC - 04:30) Caracas') }}</option>
            <option value="America/Cayenne">{{ $t('(GMT/UTC - 03:00) Cayenne') }}</option>
            <option value="America/Cayman">{{ $t('(GMT/UTC - 05:00) Cayman') }}</option>
            <option value="America/Chicago">{{ $t('(GMT/UTC - 06:00) Chicago') }}</option>
            <option value="America/Chihuahua">{{ $t('(GMT/UTC - 07:00) Chihuahua') }}</option>
            <option value="America/Costa_Rica">{{ $t('(GMT/UTC - 06:00) Costa Rica') }}</option>
            <option value="America/Creston">{{ $t('(GMT/UTC - 07:00) Creston') }}</option>
            <option value="America/Cuiaba">{{ $t('(GMT/UTC - 03:00) Cuiaba') }}</option>
            <option value="America/Curacao">{{ $t('(GMT/UTC - 04:00) Curacao') }}</option>
            <option value="America/Danmarkshavn">{{ $t('(GMT/UTC + 00:00) Danmarkshavn') }}</option>
            <option value="America/Dawson">{{ $t('(GMT/UTC - 08:00) Dawson') }}</option>
            <option value="America/Dawson_Creek">{{ $t('(GMT/UTC - 07:00) Dawson Creek') }}</option>
            <option value="America/Denver">{{ $t('(GMT/UTC - 07:00) Denver') }}</option>
            <option value="America/Detroit">{{ $t('(GMT/UTC - 05:00) Detroit') }}</option>
            <option value="America/Dominica">{{ $t('(GMT/UTC - 04:00) Dominica') }}</option>
            <option value="America/Edmonton">{{ $t('(GMT/UTC - 07:00) Edmonton') }}</option>
            <option value="America/Eirunepe">{{ $t('(GMT/UTC - 05:00) Eirunepe') }}</option>
            <option value="America/El_Salvador">{{ $t('(GMT/UTC - 06:00) El Salvador') }}</option>
            <option value="America/Fort_Nelson">{{ $t('(GMT/UTC - 07:00) Fort Nelson') }}</option>
            <option value="America/Fortaleza">{{ $t('(GMT/UTC - 03:00) Fortaleza') }}</option>
            <option value="America/Glace_Bay">{{ $t('(GMT/UTC - 04:00) Glace Bay') }}</option>
            <option value="America/Godthab">{{ $t('(GMT/UTC - 03:00) Godthab') }}</option>
            <option value="America/Goose_Bay">{{ $t('(GMT/UTC - 04:00) Goose Bay') }}</option>
            <option value="America/Grand_Turk">{{ $t('(GMT/UTC - 04:00) Grand Turk') }}</option>
            <option value="America/Grenada">{{ $t('(GMT/UTC - 04:00) Grenada') }}</option>
            <option value="America/Guadeloupe">{{ $t('(GMT/UTC - 04:00) Guadeloupe') }}</option>
            <option value="America/Guatemala">{{ $t('(GMT/UTC - 06:00) Guatemala') }}</option>
            <option value="America/Guayaquil">{{ $t('(GMT/UTC - 05:00) Guayaquil') }}</option>
            <option value="America/Guyana">{{ $t('(GMT/UTC - 04:00) Guyana') }}</option>
            <option value="America/Halifax">{{ $t('(GMT/UTC - 04:00) Halifax') }}</option>
            <option value="America/Havana">{{ $t('(GMT/UTC - 05:00) Havana') }}</option>
            <option value="America/Hermosillo">{{ $t('(GMT/UTC - 07:00) Hermosillo') }}</option>
            <option value="America/Indiana/Indianapolis">{{ $t('(GMT/UTC - 05:00) Indiana/Indianapolis') }}</option>
            <option value="America/Indiana/Knox">{{ $t('(GMT/UTC - 06:00) Indiana/Knox') }}</option>
            <option value="America/Indiana/Marengo">{{ $t('(GMT/UTC - 05:00) Indiana/Marengo') }}</option>
            <option value="America/Indiana/Petersburg">{{ $t('(GMT/UTC - 05:00) Indiana/Petersburg') }}</option>
            <option value="America/Indiana/Tell_City">{{ $t('(GMT/UTC - 06:00) Indiana/Tell City') }}</option>
            <option value="America/Indiana/Vevay">{{ $t('(GMT/UTC - 05:00) Indiana/Vevay') }}</option>
            <option value="America/Indiana/Vincennes">{{ $t('(GMT/UTC - 05:00) Indiana/Vincennes') }}</option>
            <option value="America/Indiana/Winamac">{{ $t('(GMT/UTC - 05:00) Indiana/Winamac') }}</option>
            <option value="America/Inuvik">{{ $t('(GMT/UTC - 07:00) Inuvik') }}</option>
            <option value="America/Iqaluit">{{ $t('(GMT/UTC - 05:00) Iqaluit') }}</option>
            <option value="America/Jamaica">{{ $t('(GMT/UTC - 05:00) Jamaica') }}</option>
            <option value="America/Juneau">{{ $t('(GMT/UTC - 09:00) Juneau') }}</option>
            <option value="America/Kentucky/Louisville">{{ $t('(GMT/UTC - 05:00) Kentucky/Louisville') }}</option>
            <option value="America/Kentucky/Monticello">{{ $t('(GMT/UTC - 05:00) Kentucky/Monticello') }}</option>
            <option value="America/Kralendijk">{{ $t('(GMT/UTC - 04:00) Kralendijk') }}</option>
            <option value="America/La_Paz">{{ $t('(GMT/UTC - 04:00) La Paz') }}</option>
            <option value="America/Lima">{{ $t('(GMT/UTC - 05:00) Lima') }}</option>
            <option value="America/Los_Angeles">{{ $t('(GMT/UTC - 08:00) Los Angeles') }}</option>
            <option value="America/Lower_Princes">{{ $t('(GMT/UTC - 04:00) Lower Princes') }}</option>
            <option value="America/Maceio">{{ $t('(GMT/UTC - 03:00) Maceio') }}</option>
            <option value="America/Managua">{{ $t('(GMT/UTC - 06:00) Managua') }}</option>
            <option value="America/Manaus">{{ $t('(GMT/UTC - 04:00) Manaus') }}</option>
            <option value="America/Marigot">{{ $t('(GMT/UTC - 04:00) Marigot') }}</option>
            <option value="America/Martinique">{{ $t('(GMT/UTC - 04:00) Martinique') }}</option>
            <option value="America/Matamoros">{{ $t('(GMT/UTC - 06:00) Matamoros') }}</option>
            <option value="America/Mazatlan">{{ $t('(GMT/UTC - 07:00) Mazatlan') }}</option>
            <option value="America/Menominee">{{ $t('(GMT/UTC - 06:00) Menominee') }}</option>
            <option value="America/Merida">{{ $t('(GMT/UTC - 06:00) Merida') }}</option>
            <option value="America/Metlakatla">{{ $t('(GMT/UTC - 09:00) Metlakatla') }}</option>
            <option value="America/Mexico_City">{{ $t('(GMT/UTC - 06:00) Mexico City') }}</option>
            <option value="America/Miquelon">{{ $t('(GMT/UTC - 03:00) Miquelon') }}</option>
            <option value="America/Moncton">{{ $t('(GMT/UTC - 04:00) Moncton') }}</option>
            <option value="America/Monterrey">{{ $t('(GMT/UTC - 06:00) Monterrey') }}</option>
            <option value="America/Montevideo">{{ $t('(GMT/UTC - 03:00) Montevideo') }}</option>
            <option value="America/Montserrat">{{ $t('(GMT/UTC - 04:00) Montserrat') }}</option>
            <option value="America/Nassau">{{ $t('(GMT/UTC - 05:00) Nassau') }}</option>
            <option value="America/New_York">{{ $t('(GMT/UTC - 05:00) New York') }}</option>
            <option value="America/Nipigon">{{ $t('(GMT/UTC - 05:00) Nipigon') }}</option>
            <option value="America/Nome">{{ $t('(GMT/UTC - 09:00) Nome') }}</option>
            <option value="America/Noronha">{{ $t('(GMT/UTC - 02:00) Noronha') }}</option>
            <option value="America/North_Dakota/Beulah">{{ $t('(GMT/UTC - 06:00) North Dakota/Beulah') }}</option>
            <option value="America/North_Dakota/Center">{{ $t('(GMT/UTC - 06:00) North Dakota/Center') }}</option>
            <option value="America/North_Dakota/New_Salem">{{ $t('(GMT/UTC - 06:00) North Dakota/New Salem') }}</option>
            <option value="America/Ojinaga">{{ $t('(GMT/UTC - 07:00) Ojinaga') }}</option>
            <option value="America/Panama">{{ $t('(GMT/UTC - 05:00) Panama') }}</option>
            <option value="America/Pangnirtung">{{ $t('(GMT/UTC - 05:00) Pangnirtung') }}</option>
            <option value="America/Paramaribo">{{ $t('(GMT/UTC - 03:00) Paramaribo') }}</option>
            <option value="America/Phoenix">{{ $t('(GMT/UTC - 07:00) Phoenix') }}</option>
            <option value="America/Port-au-Prince">{{ $t('(GMT/UTC - 05:00) Port-au-Prince') }}</option>
            <option value="America/Port_of_Spain">{{ $t('(GMT/UTC - 04:00) Port of Spain') }}</option>
            <option value="America/Porto_Velho">{{ $t('(GMT/UTC - 04:00) Porto Velho') }}</option>
            <option value="America/Puerto_Rico">{{ $t('(GMT/UTC - 04:00) Puerto Rico') }}</option>
            <option value="America/Rainy_River">{{ $t('(GMT/UTC - 06:00) Rainy River') }}</option>
            <option value="America/Rankin_Inlet">{{ $t('(GMT/UTC - 06:00) Rankin Inlet') }}</option>
            <option value="America/Recife">{{ $t('(GMT/UTC - 03:00) Recife') }}</option>
            <option value="America/Regina">{{ $t('(GMT/UTC - 06:00) Regina') }}</option>
            <option value="America/Resolute">{{ $t('(GMT/UTC - 06:00) Resolute') }}</option>
            <option value="America/Rio_Branco">{{ $t('(GMT/UTC - 05:00) Rio Branco') }}</option>
            <option value="America/Santarem">{{ $t('(GMT/UTC - 03:00) Santarem') }}</option>
            <option value="America/Santiago">{{ $t('(GMT/UTC - 03:00) Santiago') }}</option>
            <option value="America/Santo_Domingo">{{ $t('(GMT/UTC - 04:00) Santo Domingo') }}</option>
            <option value="America/Sao_Paulo">{{ $t('(GMT/UTC - 02:00) Sao Paulo') }}</option>
            <option value="America/Scoresbysund">{{ $t('(GMT/UTC - 01:00) Scoresbysund') }}</option>
            <option value="America/Sitka">{{ $t('(GMT/UTC - 09:00) Sitka') }}</option>
            <option value="America/St_Barthelemy">{{ $t('(GMT/UTC - 04:00) St. Barthelemy') }}</option>
            <option value="America/St_Johns">{{ $t('(GMT/UTC - 03:30) St. Johns') }}</option>
            <option value="America/St_Kitts">{{ $t('(GMT/UTC - 04:00) St. Kitts') }}</option>
            <option value="America/St_Lucia">{{ $t('(GMT/UTC - 04:00) St. Lucia') }}</option>
            <option value="America/St_Thomas">{{ $t('(GMT/UTC - 04:00) St. Thomas') }}</option>
            <option value="America/St_Vincent">{{ $t('(GMT/UTC - 04:00) St. Vincent') }}</option>
            <option value="America/Swift_Current">{{ $t('(GMT/UTC - 06:00) Swift Current') }}</option>
            <option value="America/Tegucigalpa">{{ $t('(GMT/UTC - 06:00) Tegucigalpa') }}</option>
            <option value="America/Thule">{{ $t('(GMT/UTC - 04:00) Thule') }}</option>
            <option value="America/Thunder_Bay">{{ $t('(GMT/UTC - 05:00) Thunder Bay') }}</option>
            <option value="America/Tijuana">{{ $t('(GMT/UTC - 08:00) Tijuana') }}</option>
            <option value="America/Toronto">{{ $t('(GMT/UTC - 05:00) Toronto') }}</option>
            <option value="America/Tortola">{{ $t('(GMT/UTC - 04:00) Tortola') }}</option>
            <option value="America/Vancouver">{{ $t('(GMT/UTC - 08:00) Vancouver') }}</option>
            <option value="America/Whitehorse">{{ $t('(GMT/UTC - 08:00) Whitehorse') }}</option>
            <option value="America/Winnipeg">{{ $t('(GMT/UTC - 06:00) Winnipeg') }}</option>
            <option value="America/Yakutat">{{ $t('(GMT/UTC - 09:00) Yakutat') }}</option>
            <option value="America/Yellowknife">{{ $t('(GMT/UTC - 07:00) Yellowknife') }}</option>
          </optgroup>
          <optgroup :label="$t('Europe')">
            <option value="Europe/Amsterdam">{{ $t('(GMT/UTC + 01:00) Amsterdam') }}</option>
            <option value="Europe/Andorra">{{ $t('(GMT/UTC + 01:00) Andorra') }}</option>
            <option value="Europe/Astrakhan">{{ $t('(GMT/UTC + 04:00) Astrakhan') }}</option>
            <option value="Europe/Athens">{{ $t('(GMT/UTC + 02:00) Athens') }}</option>
            <option value="Europe/Belgrade">{{ $t('(GMT/UTC + 01:00) Belgrade') }}</option>
            <option value="Europe/Berlin">{{ $t('(GMT/UTC + 01:00) Berlin') }}</option>
            <option value="Europe/Bratislava">{{ $t('(GMT/UTC + 01:00) Bratislava') }}</option>
            <option value="Europe/Brussels">{{ $t('(GMT/UTC + 01:00) Brussels') }}</option>
            <option value="Europe/Bucharest">{{ $t('(GMT/UTC + 02:00) Bucharest') }}</option>
            <option value="Europe/Budapest">{{ $t('(GMT/UTC + 01:00) Budapest') }}</option>
            <option value="Europe/Busingen">{{ $t('(GMT/UTC + 01:00) Busingen') }}</option>
            <option value="Europe/Chisinau">{{ $t('(GMT/UTC + 02:00) Chisinau') }}</option>
            <option value="Europe/Copenhagen">{{ $t('(GMT/UTC + 01:00) Copenhagen') }}</option>
            <option value="Europe/Dublin">{{ $t('(GMT/UTC + 00:00) Dublin') }}</option>
            <option value="Europe/Gibraltar">{{ $t('(GMT/UTC + 01:00) Gibraltar') }}</option>
            <option value="Europe/Guernsey">{{ $t('(GMT/UTC + 00:00) Guernsey') }}</option>
            <option value="Europe/Helsinki">{{ $t('(GMT/UTC + 02:00) Helsinki') }}</option>
            <option value="Europe/Isle_of_Man">{{ $t('(GMT/UTC + 00:00) Isle of Man') }}</option>
            <option value="Europe/Istanbul">{{ $t('(GMT/UTC + 02:00) Istanbul') }}</option>
            <option value="Europe/Jersey">{{ $t('(GMT/UTC + 00:00) Jersey') }}</option>
            <option value="Europe/Kaliningrad">{{ $t('(GMT/UTC + 02:00) Kaliningrad') }}</option>
            <option value="Europe/Kiev">{{ $t('(GMT/UTC + 02:00) Kiev') }}</option>
            <option value="Europe/Lisbon">{{ $t('(GMT/UTC + 00:00) Lisbon') }}</option>
            <option value="Europe/Ljubljana">{{ $t('(GMT/UTC + 01:00) Ljubljana') }}</option>
            <option value="Europe/London">{{ $t('(GMT/UTC + 00:00) London') }}</option>
            <option value="Europe/Luxembourg">{{ $t('(GMT/UTC + 01:00) Luxembourg') }}</option>
            <option value="Europe/Madrid">{{ $t('(GMT/UTC + 01:00) Madrid') }}</option>
            <option value="Europe/Malta">{{ $t('(GMT/UTC + 01:00) Malta') }}</option>
            <option value="Europe/Mariehamn">{{ $t('(GMT/UTC + 02:00) Mariehamn') }}</option>
            <option value="Europe/Minsk">{{ $t('(GMT/UTC + 03:00) Minsk') }}</option>
            <option value="Europe/Monaco">{{ $t('(GMT/UTC + 01:00) Monaco') }}</option>
            <option value="Europe/Moscow">{{ $t('(GMT/UTC + 03:00) Moscow') }}</option>
            <option value="Europe/Oslo">{{ $t('(GMT/UTC + 01:00) Oslo') }}</option>
            <option value="Europe/Paris">{{ $t('(GMT/UTC + 01:00) Paris') }}</option>
            <option value="Europe/Podgorica">{{ $t('(GMT/UTC + 01:00) Podgorica') }}</option>
            <option value="Europe/Prague">{{ $t('(GMT/UTC + 01:00) Prague') }}</option>
            <option value="Europe/Riga">{{ $t('(GMT/UTC + 02:00) Riga') }}</option>
            <option value="Europe/Rome">{{ $t('(GMT/UTC + 01:00) Rome') }}</option>
            <option value="Europe/Samara">{{ $t('(GMT/UTC + 04:00) Samara') }}</option>
            <option value="Europe/San_Marino">{{ $t('(GMT/UTC + 01:00) San Marino') }}</option>
            <option value="Europe/Sarajevo">{{ $t('(GMT/UTC + 01:00) Sarajevo') }}</option>
            <option value="Europe/Simferopol">{{ $t('(GMT/UTC + 03:00) Simferopol') }}</option>
            <option value="Europe/Skopje">{{ $t('(GMT/UTC + 01:00) Skopje') }}</option>
            <option value="Europe/Sofia">{{ $t('(GMT/UTC + 02:00) Sofia') }}</option>
            <option value="Europe/Stockholm">{{ $t('(GMT/UTC + 01:00) Stockholm') }}</option>
            <option value="Europe/Tallinn">{{ $t('(GMT/UTC + 02:00) Tallinn') }}</option>
            <option value="Europe/Tirane">{{ $t('(GMT/UTC + 01:00) Tirane') }}</option>
            <option value="Europe/Ulyanovsk">{{ $t('(GMT/UTC + 04:00) Ulyanovsk') }}</option>
            <option value="Europe/Uzhgorod">{{ $t('(GMT/UTC + 02:00) Uzhgorod') }}</option>
            <option value="Europe/Vaduz">{{ $t('(GMT/UTC + 01:00) Vaduz') }}</option>
            <option value="Europe/Vatican">{{ $t('(GMT/UTC + 01:00) Vatican') }}</option>
            <option value="Europe/Vienna">{{ $t('(GMT/UTC + 01:00) Vienna') }}</option>
            <option value="Europe/Vilnius">{{ $t('(GMT/UTC + 02:00) Vilnius') }}</option>
            <option value="Europe/Volgograd">{{ $t('(GMT/UTC + 03:00) Volgograd') }}</option>
            <option value="Europe/Warsaw">{{ $t('(GMT/UTC + 01:00) Warsaw') }}</option>
            <option value="Europe/Zagreb">{{ $t('(GMT/UTC + 01:00) Zagreb') }}</option>
            <option value="Europe/Zaporozhye">{{ $t('(GMT/UTC + 02:00) Zaporozhye') }}</option>
            <option value="Europe/Zurich">{{ $t('(GMT/UTC + 01:00) Zurich') }}</option>
          </optgroup>
          <optgroup :label="$t('Africa')">
            <option value="Africa/Abidjan">{{ $t('(GMT/UTC + 00:00) Abidjan') }}</option>
            <option value="Africa/Accra">{{ $t('(GMT/UTC + 00:00) Accra') }}</option>
            <option value="Africa/Addis_Ababa">{{ $t('(GMT/UTC + 03:00) Addis Ababa') }}</option>
            <option value="Africa/Algiers">{{ $t('(GMT/UTC + 01:00) Algiers') }}</option>
            <option value="Africa/Asmara">{{ $t('(GMT/UTC + 03:00) Asmara') }}</option>
            <option value="Africa/Bamako">{{ $t('(GMT/UTC + 00:00) Bamako') }}</option>
            <option value="Africa/Bangui">{{ $t('(GMT/UTC + 01:00) Bangui') }}</option>
            <option value="Africa/Banjul">{{ $t('(GMT/UTC + 00:00) Banjul') }}</option>
            <option value="Africa/Bissau">{{ $t('(GMT/UTC + 00:00) Bissau') }}</option>
            <option value="Africa/Blantyre">{{ $t('(GMT/UTC + 02:00) Blantyre') }}</option>
            <option value="Africa/Brazzaville">{{ $t('(GMT/UTC + 01:00) Brazzaville') }}</option>
            <option value="Africa/Bujumbura">{{ $t('(GMT/UTC + 02:00) Bujumbura') }}</option>
            <option value="Africa/Cairo">{{ $t('(GMT/UTC + 02:00) Cairo') }}</option>
            <option value="Africa/Casablanca">{{ $t('(GMT/UTC + 00:00) Casablanca') }}</option>
            <option value="Africa/Ceuta">{{ $t('(GMT/UTC + 01:00) Ceuta') }}</option>
            <option value="Africa/Conakry">{{ $t('(GMT/UTC + 00:00) Conakry') }}</option>
            <option value="Africa/Dakar">{{ $t('(GMT/UTC + 00:00) Dakar') }}</option>
            <option value="Africa/Dar_es_Salaam">{{ $t('(GMT/UTC + 03:00) Dar es Salaam') }}</option>
            <option value="Africa/Djibouti">{{ $t('(GMT/UTC + 03:00) Djibouti') }}</option>
            <option value="Africa/Douala">{{ $t('(GMT/UTC + 01:00) Douala') }}</option>
            <option value="Africa/El_Aaiun">{{ $t('(GMT/UTC + 00:00) El Aaiun') }}</option>
            <option value="Africa/Freetown">{{ $t('(GMT/UTC + 00:00) Freetown') }}</option>
            <option value="Africa/Gaborone">{{ $t('(GMT/UTC + 02:00) Gaborone') }}</option>
            <option value="Africa/Harare">{{ $t('(GMT/UTC + 02:00) Harare') }}</option>
            <option value="Africa/Johannesburg">{{ $t('(GMT/UTC + 02:00) Johannesburg') }}</option>
            <option value="Africa/Juba">{{ $t('(GMT/UTC + 03:00) Juba') }}</option>
            <option value="Africa/Kampala">{{ $t('(GMT/UTC + 03:00) Kampala') }}</option>
            <option value="Africa/Khartoum">{{ $t('(GMT/UTC + 03:00) Khartoum') }}</option>
            <option value="Africa/Kigali">{{ $t('(GMT/UTC + 02:00) Kigali') }}</option>
            <option value="Africa/Kinshasa">{{ $t('(GMT/UTC + 01:00) Kinshasa') }}</option>
            <option value="Africa/Lagos">{{ $t('(GMT/UTC + 01:00) Lagos') }}</option>
            <option value="Africa/Libreville">{{ $t('(GMT/UTC + 01:00) Libreville') }}</option>
            <option value="Africa/Lome">{{ $t('(GMT/UTC + 00:00) Lome') }}</option>
            <option value="Africa/Luanda">{{ $t('(GMT/UTC + 01:00) Luanda') }}</option>
            <option value="Africa/Lubumbashi">{{ $t('(GMT/UTC + 02:00) Lubumbashi') }}</option>
            <option value="Africa/Lusaka">{{ $t('(GMT/UTC + 02:00) Lusaka') }}</option>
            <option value="Africa/Malabo">{{ $t('(GMT/UTC + 01:00) Malabo') }}</option>
            <option value="Africa/Maputo">{{ $t('(GMT/UTC + 02:00) Maputo') }}</option>
            <option value="Africa/Maseru">{{ $t('(GMT/UTC + 02:00) Maseru') }}</option>
            <option value="Africa/Mbabane">{{ $t('(GMT/UTC + 02:00) Mbabane') }}</option>
            <option value="Africa/Mogadishu">{{ $t('(GMT/UTC + 03:00) Mogadishu') }}</option>
            <option value="Africa/Monrovia">{{ $t('(GMT/UTC + 00:00) Monrovia') }}</option>
            <option value="Africa/Nairobi">{{ $t('(GMT/UTC + 03:00) Nairobi') }}</option>
            <option value="Africa/Ndjamena">{{ $t('(GMT/UTC + 01:00) Ndjamena') }}</option>
            <option value="Africa/Niamey">{{ $t('(GMT/UTC + 01:00) Niamey') }}</option>
            <option value="Africa/Nouakchott">{{ $t('(GMT/UTC + 00:00) Nouakchott') }}</option>
            <option value="Africa/Ouagadougou">{{ $t('(GMT/UTC + 00:00) Ouagadougou') }}</option>
            <option value="Africa/Porto-Novo">{{ $t('(GMT/UTC + 01:00) Porto-Novo') }}</option>
            <option value="Africa/Sao_Tome">{{ $t('(GMT/UTC + 00:00) Sao Tome') }}</option>
            <option value="Africa/Tripoli">{{ $t('(GMT/UTC + 02:00) Tripoli') }}</option>
            <option value="Africa/Tunis">{{ $t('(GMT/UTC + 01:00) Tunis') }}</option>
            <option value="Africa/Windhoek">{{ $t('(GMT/UTC + 02:00) Windhoek') }}</option>
          </optgroup>
          <optgroup :label="$t('Antarctica')">
            <option value="Antarctica/Casey">{{ $t('(GMT/UTC + 08:00) Casey') }}</option>
            <option value="Antarctica/Davis">{{ $t('(GMT/UTC + 07:00) Davis') }}</option>
            <option value="Antarctica/DumontDUrville">{{ $t('(GMT/UTC + 10:00) DumontDUrville') }}</option>
            <option value="Antarctica/Macquarie">{{ $t('(GMT/UTC + 11:00) Macquarie') }}</option>
            <option value="Antarctica/Mawson">{{ $t('(GMT/UTC + 05:00) Mawson') }}</option>
            <option value="Antarctica/McMurdo">{{ $t('(GMT/UTC + 13:00) McMurdo') }}</option>
            <option value="Antarctica/Palmer">{{ $t('(GMT/UTC - 03:00) Palmer') }}</option>
            <option value="Antarctica/Rothera">{{ $t('(GMT/UTC - 03:00) Rothera') }}</option>
            <option value="Antarctica/Syowa">{{ $t('(GMT/UTC + 03:00) Syowa') }}</option>
            <option value="Antarctica/Troll">{{ $t('(GMT/UTC + 00:00) Troll') }}</option>
            <option value="Antarctica/Vostok">{{ $t('(GMT/UTC + 06:00) Vostok') }}</option>
          </optgroup>
          <optgroup :label="$t('Arctic')">
            <option value="Arctic/Longyearbyen">{{ $t('(GMT/UTC + 01:00) Longyearbyen') }}</option>
          </optgroup>
          <optgroup :label="$t('Asia')">
            <option value="Asia/Aden">{{ $t('(GMT/UTC + 03:00) Aden') }}</option>
            <option value="Asia/Almaty">{{ $t('(GMT/UTC + 06:00) Almaty') }}</option>
            <option value="Asia/Amman">{{ $t('(GMT/UTC + 02:00) Amman') }}</option>
            <option value="Asia/Anadyr">{{ $t('(GMT/UTC + 12:00) Anadyr') }}</option>
            <option value="Asia/Aqtau">{{ $t('(GMT/UTC + 05:00) Aqtau') }}</option>
            <option value="Asia/Aqtobe">{{ $t('(GMT/UTC + 05:00) Aqtobe') }}</option>
            <option value="Asia/Ashgabat">{{ $t('(GMT/UTC + 05:00) Ashgabat') }}</option>
            <option value="Asia/Baghdad">{{ $t('(GMT/UTC + 03:00) Baghdad') }}</option>
            <option value="Asia/Bahrain">{{ $t('(GMT/UTC + 03:00) Bahrain') }}</option>
            <option value="Asia/Baku">{{ $t('(GMT/UTC + 04:00) Baku') }}</option>
            <option value="Asia/Bangkok">{{ $t('(GMT/UTC + 07:00) Bangkok') }}</option>
            <option value="Asia/Barnaul">{{ $t('(GMT/UTC + 07:00) Barnaul') }}</option>
            <option value="Asia/Beirut">{{ $t('(GMT/UTC + 02:00) Beirut') }}</option>
            <option value="Asia/Bishkek">{{ $t('(GMT/UTC + 06:00) Bishkek') }}</option>
            <option value="Asia/Brunei">{{ $t('(GMT/UTC + 08:00) Brunei') }}</option>
            <option value="Asia/Chita">{{ $t('(GMT/UTC + 09:00) Chita') }}</option>
            <option value="Asia/Choibalsan">{{ $t('(GMT/UTC + 08:00) Choibalsan') }}</option>
            <option value="Asia/Colombo">{{ $t('(GMT/UTC + 05:30) Colombo') }}</option>
            <option value="Asia/Damascus">{{ $t('(GMT/UTC + 02:00) Damascus') }}</option>
            <option value="Asia/Dhaka">{{ $t('(GMT/UTC + 06:00) Dhaka') }}</option>
            <option value="Asia/Dili">{{ $t('(GMT/UTC + 09:00) Dili') }}</option>
            <option value="Asia/Dubai">{{ $t('(GMT/UTC + 04:00) Dubai') }}</option>
            <option value="Asia/Dushanbe">{{ $t('(GMT/UTC + 05:00) Dushanbe') }}</option>
            <option value="Asia/Gaza">{{ $t('(GMT/UTC + 02:00) Gaza') }}</option>
            <option value="Asia/Hebron">{{ $t('(GMT/UTC + 02:00) Hebron') }}</option>
            <option value="Asia/Ho_Chi_Minh">{{ $t('(GMT/UTC + 07:00) Ho Chi Minh') }}</option>
            <option value="Asia/Hong_Kong">{{ $t('(GMT/UTC + 08:00) Hong Kong') }}</option>
            <option value="Asia/Hovd">{{ $t('(GMT/UTC + 07:00) Hovd') }}</option>
            <option value="Asia/Irkutsk">{{ $t('(GMT/UTC + 08:00) Irkutsk') }}</option>
            <option value="Asia/Jakarta">{{ $t('(GMT/UTC + 07:00) Jakarta') }}</option>
            <option value="Asia/Jayapura">{{ $t('(GMT/UTC + 09:00) Jayapura') }}</option>
            <option value="Asia/Jerusalem">{{ $t('(GMT/UTC + 02:00) Jerusalem') }}</option>
            <option value="Asia/Kabul">{{ $t('(GMT/UTC + 04:30) Kabul') }}</option>
            <option value="Asia/Kamchatka">{{ $t('(GMT/UTC + 12:00) Kamchatka') }}</option>
            <option value="Asia/Karachi">{{ $t('(GMT/UTC + 05:00) Karachi') }}</option>
            <option value="Asia/Kathmandu">{{ $t('(GMT/UTC + 05:45) Kathmandu') }}</option>
            <option value="Asia/Khandyga">{{ $t('(GMT/UTC + 09:00) Khandyga') }}</option>
            <option value="Asia/Kolkata">{{ $t('(GMT/UTC + 05:30) Kolkata') }}</option>
            <option value="Asia/Krasnoyarsk">{{ $t('(GMT/UTC + 07:00) Krasnoyarsk') }}</option>
            <option value="Asia/Kuala_Lumpur">{{ $t('(GMT/UTC + 08:00) Kuala Lumpur') }}</option>
            <option value="Asia/Kuching">{{ $t('(GMT/UTC + 08:00) Kuching') }}</option>
            <option value="Asia/Kuwait">{{ $t('(GMT/UTC + 03:00) Kuwait') }}</option>
            <option value="Asia/Macau">{{ $t('(GMT/UTC + 08:00) Macau') }}</option>
            <option value="Asia/Magadan">{{ $t('(GMT/UTC + 10:00) Magadan') }}</option>
            <option value="Asia/Makassar">{{ $t('(GMT/UTC + 08:00) Makassar') }}</option>
            <option value="Asia/Manila">{{ $t('(GMT/UTC + 08:00) Manila') }}</option>
            <option value="Asia/Muscat">{{ $t('(GMT/UTC + 04:00) Muscat') }}</option>
            <option value="Asia/Nicosia">{{ $t('(GMT/UTC + 02:00) Nicosia') }}</option>
            <option value="Asia/Novokuznetsk">{{ $t('(GMT/UTC + 07:00) Novokuznetsk') }}</option>
            <option value="Asia/Novosibirsk">{{ $t('(GMT/UTC + 06:00) Novosibirsk') }}</option>
            <option value="Asia/Omsk">{{ $t('(GMT/UTC + 06:00) Omsk') }}</option>
            <option value="Asia/Oral">{{ $t('(GMT/UTC + 05:00) Oral') }}</option>
            <option value="Asia/Phnom_Penh">{{ $t('(GMT/UTC + 07:00) Phnom Penh') }}</option>
            <option value="Asia/Pontianak">{{ $t('(GMT/UTC + 07:00) Pontianak') }}</option>
            <option value="Asia/Pyongyang">{{ $t('(GMT/UTC + 08:30) Pyongyang') }}</option>
            <option value="Asia/Qatar">{{ $t('(GMT/UTC + 03:00) Qatar') }}</option>
            <option value="Asia/Qyzylorda">{{ $t('(GMT/UTC + 06:00) Qyzylorda') }}</option>
            <option value="Asia/Rangoon">{{ $t('(GMT/UTC + 06:30) Rangoon') }}</option>
            <option value="Asia/Riyadh">{{ $t('(GMT/UTC + 03:00) Riyadh') }}</option>
            <option value="Asia/Sakhalin">{{ $t('(GMT/UTC + 11:00) Sakhalin') }}</option>
            <option value="Asia/Samarkand">{{ $t('(GMT/UTC + 05:00) Samarkand') }}</option>
            <option value="Asia/Seoul">{{ $t('(GMT/UTC + 09:00) Seoul') }}</option>
            <option value="Asia/Shanghai">{{ $t('(GMT/UTC + 08:00) Shanghai') }}</option>
            <option value="Asia/Singapore">{{ $t('(GMT/UTC + 08:00) Singapore') }}</option>
            <option value="Asia/Srednekolymsk">{{ $t('(GMT/UTC + 11:00) Srednekolymsk') }}</option>
            <option value="Asia/Taipei">{{ $t('(GMT/UTC + 08:00) Taipei') }}</option>
            <option value="Asia/Tashkent">{{ $t('(GMT/UTC + 05:00) Tashkent') }}</option>
            <option value="Asia/Tbilisi">{{ $t('(GMT/UTC + 04:00) Tbilisi') }}</option>
            <option value="Asia/Tehran">{{ $t('(GMT/UTC + 03:30) Tehran') }}</option>
            <option value="Asia/Thimphu">{{ $t('(GMT/UTC + 06:00) Thimphu') }}</option>
            <option value="Asia/Tokyo">{{ $t('(GMT/UTC + 09:00) Tokyo') }}</option>
            <option value="Asia/Ulaanbaatar">{{ $t('(GMT/UTC + 08:00) Ulaanbaatar') }}</option>
            <option value="Asia/Urumqi">{{ $t('(GMT/UTC + 06:00) Urumqi') }}</option>
            <option value="Asia/Ust-Nera">{{ $t('(GMT/UTC + 10:00) Ust-Nera') }}</option>
            <option value="Asia/Vientiane">{{ $t('(GMT/UTC + 07:00) Vientiane') }}</option>
            <option value="Asia/Vladivostok">{{ $t('(GMT/UTC + 10:00) Vladivostok') }}</option>
            <option value="Asia/Yakutsk">{{ $t('(GMT/UTC + 09:00) Yakutsk') }}</option>
            <option value="Asia/Yekaterinburg">{{ $t('(GMT/UTC + 05:00) Yekaterinburg') }}</option>
            <option value="Asia/Yerevan">{{ $t('(GMT/UTC + 04:00) Yerevan') }}</option>
          </optgroup>
          <optgroup :label="$t('Atlantic')">
            <option value="Atlantic/Azores">{{ $t('(GMT/UTC - 01:00) Azores') }}</option>
            <option value="Atlantic/Bermuda">{{ $t('(GMT/UTC - 04:00) Bermuda') }}</option>
            <option value="Atlantic/Canary">{{ $t('(GMT/UTC + 00:00) Canary') }}</option>
            <option value="Atlantic/Cape_Verde">{{ $t('(GMT/UTC - 01:00) Cape Verde') }}</option>
            <option value="Atlantic/Faroe">{{ $t('(GMT/UTC + 00:00) Faroe') }}</option>
            <option value="Atlantic/Madeira">{{ $t('(GMT/UTC + 00:00) Madeira') }}</option>
            <option value="Atlantic/Reykjavik">{{ $t('(GMT/UTC + 00:00) Reykjavik') }}</option>
            <option value="Atlantic/South_Georgia">{{ $t('(GMT/UTC - 02:00) South Georgia') }}</option>
            <option value="Atlantic/St_Helena">{{ $t('(GMT/UTC + 00:00) St. Helena') }}</option>
            <option value="Atlantic/Stanley">{{ $t('(GMT/UTC - 03:00) Stanley') }}</option>
          </optgroup>
          <optgroup :label="$t('Australia')">
            <option value="Australia/Adelaide">{{ $t('(GMT/UTC + 10:30) Adelaide') }}</option>
            <option value="Australia/Brisbane">{{ $t('(GMT/UTC + 10:00) Brisbane') }}</option>
            <option value="Australia/Broken_Hill">{{ $t('(GMT/UTC + 10:30) Broken Hill') }}</option>
            <option value="Australia/Currie">{{ $t('(GMT/UTC + 11:00) Currie') }}</option>
            <option value="Australia/Darwin">{{ $t('(GMT/UTC + 09:30) Darwin') }}</option>
            <option value="Australia/Eucla">{{ $t('(GMT/UTC + 08:45) Eucla') }}</option>
            <option value="Australia/Hobart">{{ $t('(GMT/UTC + 11:00) Hobart') }}</option>
            <option value="Australia/Lindeman">{{ $t('(GMT/UTC + 10:00) Lindeman') }}</option>
            <option value="Australia/Lord_Howe">{{ $t('(GMT/UTC + 11:00) Lord Howe') }}</option>
            <option value="Australia/Melbourne">{{ $t('(GMT/UTC + 11:00) Melbourne') }}</option>
            <option value="Australia/Perth">{{ $t('(GMT/UTC + 08:00) Perth') }}</option>
            <option value="Australia/Sydney">{{ $t('(GMT/UTC + 11:00) Sydney') }}</option>
          </optgroup>
          <optgroup :label="$t('Indian')">
            <option value="Indian/Antananarivo">{{ $t('(GMT/UTC + 03:00) Antananarivo') }}</option>
            <option value="Indian/Chagos">{{ $t('(GMT/UTC + 06:00) Chagos') }}</option>
            <option value="Indian/Christmas">{{ $t('(GMT/UTC + 07:00) Christmas') }}</option>
            <option value="Indian/Cocos">{{ $t('(GMT/UTC + 06:30) Cocos') }}</option>
            <option value="Indian/Comoro">{{ $t('(GMT/UTC + 03:00) Comoro') }}</option>
            <option value="Indian/Kerguelen">{{ $t('(GMT/UTC + 05:00) Kerguelen') }}</option>
            <option value="Indian/Mahe">{{ $t('(GMT/UTC + 04:00) Mahe') }}</option>
            <option value="Indian/Maldives">{{ $t('(GMT/UTC + 05:00) Maldives') }}</option>
            <option value="Indian/Mauritius">{{ $t('(GMT/UTC + 04:00) Mauritius') }}</option>
            <option value="Indian/Mayotte">{{ $t('(GMT/UTC + 03:00) Mayotte') }}</option>
            <option value="Indian/Reunion">{{ $t('(GMT/UTC + 04:00) Reunion') }}</option>
          </optgroup>
          <optgroup :label="$t('Pacific')">
            <option value="Pacific/Apia">{{ $t('(GMT/UTC + 14:00) Apia') }}</option>
            <option value="Pacific/Auckland">{{ $t('(GMT/UTC + 13:00) Auckland') }}</option>
            <option value="Pacific/Bougainville">{{ $t('(GMT/UTC + 11:00) Bougainville') }}</option>
            <option value="Pacific/Chatham">{{ $t('(GMT/UTC + 13:45) Chatham') }}</option>
            <option value="Pacific/Chuuk">{{ $t('(GMT/UTC + 10:00) Chuuk') }}</option>
            <option value="Pacific/Easter">{{ $t('(GMT/UTC - 05:00) Easter') }}</option>
            <option value="Pacific/Efate">{{ $t('(GMT/UTC + 11:00) Efate') }}</option>
            <option value="Pacific/Enderbury">{{ $t('(GMT/UTC + 13:00) Enderbury') }}</option>
            <option value="Pacific/Fakaofo">{{ $t('(GMT/UTC + 13:00) Fakaofo') }}</option>
            <option value="Pacific/Fiji">{{ $t('(GMT/UTC + 12:00) Fiji') }}</option>
            <option value="Pacific/Funafuti">{{ $t('(GMT/UTC + 12:00) Funafuti') }}</option>
            <option value="Pacific/Galapagos">{{ $t('(GMT/UTC - 06:00) Galapagos') }}</option>
            <option value="Pacific/Gambier">{{ $t('(GMT/UTC - 09:00) Gambier') }}</option>
            <option value="Pacific/Guadalcanal">{{ $t('(GMT/UTC + 11:00) Guadalcanal') }}</option>
            <option value="Pacific/Guam">{{ $t('(GMT/UTC + 10:00) Guam') }}</option>
            <option value="Pacific/Honolulu">{{ $t('(GMT/UTC - 10:00) Honolulu') }}</option>
            <option value="Pacific/Johnston">{{ $t('(GMT/UTC - 10:00) Johnston') }}</option>
            <option value="Pacific/Kiritimati">{{ $t('(GMT/UTC + 14:00) Kiritimati') }}</option>
            <option value="Pacific/Kosrae">{{ $t('(GMT/UTC + 11:00) Kosrae') }}</option>
            <option value="Pacific/Kwajalein">{{ $t('(GMT/UTC + 12:00) Kwajalein') }}</option>
            <option value="Pacific/Majuro">{{ $t('(GMT/UTC + 12:00) Majuro') }}</option>
            <option value="Pacific/Marquesas">{{ $t('(GMT/UTC - 09:30) Marquesas') }}</option>
            <option value="Pacific/Midway">{{ $t('(GMT/UTC - 11:00) Midway') }}</option>
            <option value="Pacific/Nauru">{{ $t('(GMT/UTC + 12:00) Nauru') }}</option>
            <option value="Pacific/Niue">{{ $t('(GMT/UTC - 11:00) Niue') }}</option>
            <option value="Pacific/Norfolk">{{ $t('(GMT/UTC + 11:00) Norfolk') }}</option>
            <option value="Pacific/Noumea">{{ $t('(GMT/UTC + 11:00) Noumea') }}</option>
            <option value="Pacific/Pago_Pago">{{ $t('(GMT/UTC - 11:00) Pago Pago') }}</option>
            <option value="Pacific/Palau">{{ $t('(GMT/UTC + 09:00) Palau') }}</option>
            <option value="Pacific/Pitcairn">{{ $t('(GMT/UTC - 08:00) Pitcairn') }}</option>
            <option value="Pacific/Pohnpei">{{ $t('(GMT/UTC + 11:00) Pohnpei') }}</option>
            <option value="Pacific/Port_Moresby">{{ $t('(GMT/UTC + 10:00) Port Moresby') }}</option>
            <option value="Pacific/Rarotonga">{{ $t('(GMT/UTC - 10:00) Rarotonga') }}</option>
            <option value="Pacific/Saipan">{{ $t('(GMT/UTC + 10:00) Saipan') }}</option>
            <option value="Pacific/Tahiti">{{ $t('(GMT/UTC - 10:00) Tahiti') }}</option>
            <option value="Pacific/Tarawa">{{ $t('(GMT/UTC + 12:00) Tarawa') }}</option>
            <option value="Pacific/Tongatapu">{{ $t('(GMT/UTC + 13:00) Tongatapu') }}</option>
            <option value="Pacific/Wake">{{ $t('(GMT/UTC + 12:00) Wake') }}</option>
            <option value="Pacific/Wallis">{{ $t('(GMT/UTC + 12:00) Wallis') }}</option>
          </optgroup>
        </select>
      </div>

      <!-- actions -->
      <div class="flex justify-between p-5">
        <pretty-link :text="$t('Cancel')" :class="'me-3'" @click="editMode = false" />
        <pretty-button :text="$t('Save')" :state="loadingState" :icon="'check'" :class="'save'" />
      </div>
    </form>
  </div>
</template>

<script>
import PrettyButton from '@/Shared/Form/PrettyButton.vue';
import PrettyLink from '@/Shared/Form/PrettyLink.vue';
import Errors from '@/Shared/Form/Errors.vue';
import Help from '@/Shared/Help.vue';

export default {
  components: {
    PrettyButton,
    PrettyLink,
    Errors,
    Help,
  },

  props: {
    data: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
      editMode: false,
      localTimezone: '',
      form: {
        timezone: '',
        errors: [],
      },
    };
  },

  mounted() {
    this.localTimezone = this.data.timezone;
    this.form.timezone = this.data.timezone;
  },

  methods: {
    enableEditMode() {
      this.editMode = true;
    },

    submit() {
      this.loadingState = 'loading';

      axios
        .post(this.data.url.store, this.form)
        .then((response) => {
          this.flash(this.$t('Changes saved'), 'success');
          this.localTimezone = response.data.data.timezone;
          this.editMode = false;
          this.loadingState = null;
        })
        .catch((error) => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  },
};
</script>

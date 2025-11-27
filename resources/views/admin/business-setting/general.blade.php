@extends('layouts.admin.app')

@section('title', translate('General Settings'))

@section('css')
@stop

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">{{ translate('General Settings') }}</h4>

        <!-- User Pills -->
        <ul class="nav nav-pills flex-column flex-md-row mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="{{route('admin.business-setting.views', ['tab'=> 'info'])}}">
                        <span class="iconify me-1" data-icon="material-symbols:info-outline-rounded"
                              data-width="25"
                              data-height="25"></span>
                    {{ translate('General Settings') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.business-setting.views', ['tab'=> 'mail'])}}">
                        <span class="iconify me-1" data-icon="material-symbols:mail-outline"
                              data-width="25" data-height="25"></span>
                    {{ translate('E-mail Settings') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.business-setting.views', ['tab'=> 'recaptcha'])}}">
                        <span class="iconify me-1" data-icon="logos:recaptcha"
                              data-width="25" data-height="25"></span>
                    {{ translate('ReCaptcha Settings') }}
                </a>
            </li>

        </ul>


        <form action="{{route('admin.business-setting.general-update')}}" method="post"
              enctype="multipart/form-data">
            @csrf
            <!-- General Info -->
            <div class="card mb-3">
                <div class="card-header border-bottom mb-2">
                    <h4 class="card-title">
                            <span class="card-header-icon mr-1">
                                <span class="iconify" data-icon="material-symbols:info-outline-rounded"
                                      data-width="25" data-height="25"></span>
                            </span>
                        {{ translate('General Settings') }}
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            @php($config=get_business_settings('business_name', false))

                            <div class="form-group">
                                <label class="form-label" for="business_name">{{ translate('Business Name') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                       placeholder="{{ translate('Business Name') }}"
                                       value="{{ old('business_name', $config) }}" minlength="3"
                                       maxlength="255"
                                       name="business_name" id="business_name" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            @php($config=get_business_settings('phone', false))

                            <div class="form-group">
                                <label class="form-label" for="phone">{{ translate('Phone Number') }}</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="{{ translate('Phone Number') }}"
                                       value="{{ old('phone', $config) }}"
                                       minlength="3"
                                       maxlength="255"
                                       name="phone" id="phone">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            @php($config=get_business_settings('email', false))

                            <div class="form-group">
                                <label class="form-label" for="email">{{ translate('E-mail') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       placeholder="{{ translate('E-mail') }}"
                                       value="{{ old('email', $config) }}"
                                       minlength="3"
                                       name="email" id="email">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            @php($config=get_business_settings('languages') ?? ['en'])
                            <div class="form-group">
                                <label class="form-label" for="languages">{{ translate('Languages') }}</label>
                                <select id="languages" name="languages[]" class="form-control select2" multiple>
                                    <option value="af" {{in_array('af', $config) ? 'selected' : ''}}>Afrikaans
                                    </option>
                                    <option value="sq" {{in_array('sq', $config) ? 'selected' : ''}}>Albanian -
                                        shqip
                                    </option>
                                    <option value="am" {{in_array('am', $config) ? 'selected' : ''}}>Amharic -
                                        አማርኛ
                                    </option>
                                    <option value="ar" {{in_array('ar', $config) ? 'selected' : ''}}>Arabic -
                                        العربية
                                    </option>
                                    <option value="an" {{in_array('an', $config) ? 'selected' : ''}}>Aragonese -
                                        aragonés
                                    </option>
                                    <option value="hy" {{in_array('hy', $config) ? 'selected' : ''}}>Armenian -
                                        հայերեն
                                    </option>
                                    <option value="ast" {{in_array('ast', $config) ? 'selected' : ''}}>Asturian -
                                        asturianu
                                    </option>
                                    <option value="az" {{in_array('az', $config) ? 'selected' : ''}}>Azerbaijani -
                                        azərbaycan dili
                                    </option>
                                    <option value="eu" {{in_array('eu', $config) ? 'selected' : ''}}>Basque -
                                        euskara
                                    </option>
                                    <option value="be" {{in_array('be', $config) ? 'selected' : ''}}>Belarusian -
                                        беларуская
                                    </option>
                                    <option value="bn" {{in_array('bn', $config) ? 'selected' : ''}}>Bengali -
                                        বাংলা
                                    </option>
                                    <option value="bs" {{in_array('bs', $config) ? 'selected' : ''}}>Bosnian -
                                        bosanski
                                    </option>
                                    <option value="br" {{in_array('br', $config) ? 'selected' : ''}}>Breton -
                                        brezhoneg
                                    </option>
                                    <option value="bg" {{in_array('bg', $config) ? 'selected' : ''}}>Bulgarian -
                                        български
                                    </option>
                                    <option value="ca" {{in_array('ca', $config) ? 'selected' : ''}}>Catalan -
                                        català
                                    </option>
                                    <option value="ckb" {{in_array('ckb', $config) ? 'selected' : ''}}>Central
                                        Kurdish - کوردی (دەستنوسی عەرەبی)
                                    </option>
                                    <option value="zh" {{in_array('zh', $config) ? 'selected' : ''}}>Chinese -
                                        中文
                                    </option>
                                    <option value="co" {{in_array('co', $config) ? 'selected' : ''}}>Corsican
                                    </option>
                                    <option value="hr" {{in_array('hr', $config) ? 'selected' : ''}}>Croatian -
                                        hrvatski
                                    </option>
                                    <option value="cs" {{in_array('cs', $config) ? 'selected' : ''}}>Czech -
                                        čeština
                                    </option>
                                    <option value="da" {{in_array('da', $config) ? 'selected' : ''}}>Danish -
                                        dansk
                                    </option>
                                    <option value="nl" {{in_array('nl', $config) ? 'selected' : ''}}>Dutch -
                                        Nederlands
                                    </option>
                                    <option value="en" {{in_array('en', $config) ? 'selected' : ''}}>English (United
                                        States)
                                    </option>
                                    <option value="eo" {{in_array('eo', $config) ? 'selected' : ''}}>Esperanto -
                                        esperanto
                                    </option>
                                    <option value="et" {{in_array('et', $config) ? 'selected' : ''}}>Estonian -
                                        eesti
                                    </option>
                                    <option value="fo" {{in_array('fo', $config) ? 'selected' : ''}}>Faroese -
                                        føroyskt
                                    </option>
                                    <option value="fil" {{in_array('fil', $config) ? 'selected' : ''}}>Filipino
                                    </option>
                                    <option value="fi" {{in_array('fi', $config) ? 'selected' : ''}}>Finnish -
                                        suomi
                                    </option>
                                    <option value="fr" {{in_array('fr', $config) ? 'selected' : ''}}>French -
                                        français
                                    </option>
                                    <option value="gl" {{in_array('gl', $config) ? 'selected' : ''}}>Galician -
                                        galego
                                    </option>
                                    <option value="ka" {{in_array('ka', $config) ? 'selected' : ''}}>Georgian -
                                        ქართული
                                    </option>
                                    <option value="de" {{in_array('de', $config) ? 'selected' : ''}}>German -
                                        Deutsch
                                    </option>
                                    <option value="el" {{in_array('el', $config) ? 'selected' : ''}}>Greek -
                                        Ελληνικά
                                    </option>
                                    <option value="gn" {{in_array('gn', $config) ? 'selected' : ''}}>Guarani
                                    </option>
                                    <option value="gu" {{in_array('gu', $config) ? 'selected' : ''}}>Gujarati -
                                        ગુજરાતી
                                    </option>
                                    <option value="ha" {{in_array('ha', $config) ? 'selected' : ''}}>Hausa</option>
                                    <option value="haw" {{in_array('haw', $config) ? 'selected' : ''}}>Hawaiian -
                                        ʻŌlelo Hawaiʻi
                                    </option>
                                    <option value="he" {{in_array('he', $config) ? 'selected' : ''}}>Hebrew -
                                        עברית
                                    </option>
                                    <option value="hi" {{in_array('hi', $config) ? 'selected' : ''}}>Hindi -
                                        हिन्दी
                                    </option>
                                    <option value="hu" {{in_array('hu', $config) ? 'selected' : ''}}>Hungarian -
                                        magyar
                                    </option>
                                    <option value="is" {{in_array('is', $config) ? 'selected' : ''}}>Icelandic -
                                        íslenska
                                    </option>
                                    <option value="id" {{in_array('id', $config) ? 'selected' : ''}}>Indonesian -
                                        Indonesia
                                    </option>
                                    <option value="ia" {{in_array('ia', $config) ? 'selected' : ''}}>Interlingua
                                    </option>
                                    <option value="ga" {{in_array('ga', $config) ? 'selected' : ''}}>Irish -
                                        Gaeilge
                                    </option>
                                    <option value="it" {{in_array('it', $config) ? 'selected' : ''}}>Italian -
                                        italiano
                                    </option>
                                    <option value="ja" {{in_array('ja', $config) ? 'selected' : ''}}>Japanese -
                                        日本語
                                    </option>
                                    <option value="kn" {{in_array('kn', $config) ? 'selected' : ''}}>Kannada -
                                        ಕನ್ನಡ
                                    </option>
                                    <option value="kk" {{in_array('kk', $config) ? 'selected' : ''}}>Kazakh - қазақ
                                        тілі
                                    </option>
                                    <option value="km" {{in_array('km', $config) ? 'selected' : ''}}>Khmer - ខ្មែរ
                                    </option>
                                    <option value="ko" {{in_array('ko', $config) ? 'selected' : ''}}>Korean - 한국어
                                    </option>
                                    <option value="ku" {{in_array('ku', $config) ? 'selected' : ''}}>Kurdish -
                                        Kurdî
                                    </option>
                                    <option value="ky" {{in_array('ky', $config) ? 'selected' : ''}}>Kyrgyz -
                                        кыргызча
                                    </option>
                                    <option value="lo" {{in_array('lo', $config) ? 'selected' : ''}}>Lao - ລາວ
                                    </option>
                                    <option value="la" {{in_array('la', $config) ? 'selected' : ''}}>Latin</option>
                                    <option value="lv" {{in_array('lv', $config) ? 'selected' : ''}}>Latvian -
                                        latviešu
                                    </option>
                                    <option value="ln" {{in_array('ln', $config) ? 'selected' : ''}}>Lingala -
                                        lingála
                                    </option>
                                    <option value="lt" {{in_array('lt', $config) ? 'selected' : ''}}>Lithuanian -
                                        lietuvių
                                    </option>
                                    <option value="mk" {{in_array('mk', $config) ? 'selected' : ''}}>Macedonian -
                                        македонски
                                    </option>
                                    <option value="ms" {{in_array('ms', $config) ? 'selected' : ''}}>Malay - Bahasa
                                        Melayu
                                    </option>
                                    <option value="ml" {{in_array('ml', $config) ? 'selected' : ''}}>Malayalam -
                                        മലയാളം
                                    </option>
                                    <option value="mt" {{in_array('mt', $config) ? 'selected' : ''}}>Maltese -
                                        Malti
                                    </option>
                                    <option value="mr" {{in_array('mr', $config) ? 'selected' : ''}}>Marathi -
                                        मराठी
                                    </option>
                                    <option value="mn" {{in_array('mn', $config) ? 'selected' : ''}}>Mongolian -
                                        монгол
                                    </option>
                                    <option value="ne" {{in_array('ne', $config) ? 'selected' : ''}}>Nepali -
                                        नेपाली
                                    </option>
                                    <option value="no" {{in_array('no', $config) ? 'selected' : ''}}>Norwegian -
                                        norsk
                                    </option>
                                    <option value="nb" {{in_array('nb', $config) ? 'selected' : ''}}>Norwegian
                                        Bokmål - norsk bokmål
                                    </option>
                                    <option value="nn" {{in_array('nn', $config) ? 'selected' : ''}}>Norwegian
                                        Nynorsk - nynorsk
                                    </option>
                                    <option value="oc" {{in_array('oc', $config) ? 'selected' : ''}}>Occitan
                                    </option>
                                    <option value="or" {{in_array('or', $config) ? 'selected' : ''}}>Oriya - ଓଡ଼ିଆ
                                    </option>
                                    <option value="om" {{in_array('om', $config) ? 'selected' : ''}}>Oromo -
                                        Oromoo
                                    </option>
                                    <option value="ps" {{in_array('ps', $config) ? 'selected' : ''}}>Pashto - پښتو
                                    </option>
                                    <option value="fa" {{in_array('fa', $config) ? 'selected' : ''}}>Persian -
                                        فارسی
                                    </option>
                                    <option value="pl" {{in_array('pl', $config) ? 'selected' : ''}}>Polish -
                                        polski
                                    </option>
                                    <option value="pt" {{in_array('pt', $config) ? 'selected' : ''}}>Portuguese -
                                        português
                                    </option>
                                    <option value="pa" {{in_array('pa', $config) ? 'selected' : ''}}>Punjabi -
                                        ਪੰਜਾਬੀ
                                    </option>
                                    <option value="qu" {{in_array('qu', $config) ? 'selected' : ''}}>Quechua
                                    </option>
                                    <option value="ro" {{in_array('ro', $config) ? 'selected' : ''}}>Romanian -
                                        română
                                    </option>
                                    <option value="mo" {{in_array('mo', $config) ? 'selected' : ''}}>Romanian
                                        (Moldova) - română (Moldova)
                                    </option>
                                    <option value="rm" {{in_array('rm', $config) ? 'selected' : ''}}>Romansh -
                                        rumantsch
                                    </option>
                                    <option value="ru" {{in_array('ru', $config) ? 'selected' : ''}}>Russian -
                                        русский
                                    </option>
                                    <option value="gd" {{in_array('gd', $config) ? 'selected' : ''}}>Scottish
                                        Gaelic
                                    </option>
                                    <option value="sr" {{in_array('sr', $config) ? 'selected' : ''}}>Serbian -
                                        српски
                                    </option>
                                    <option value="sh" {{in_array('sh', $config) ? 'selected' : ''}}>Serbo-Croatian
                                        - Srpskohrvatski
                                    </option>
                                    <option value="sn" {{in_array('sn', $config) ? 'selected' : ''}}>Shona -
                                        chiShona
                                    </option>
                                    <option value="sd" {{in_array('sd', $config) ? 'selected' : ''}}>Sindhi</option>
                                    <option value="si" {{in_array('si', $config) ? 'selected' : ''}}>Sinhala -
                                        සිංහල
                                    </option>
                                    <option value="sk" {{in_array('sk', $config) ? 'selected' : ''}}>Slovak -
                                        slovenčina
                                    </option>
                                    <option value="sl" {{in_array('sl', $config) ? 'selected' : ''}}>Slovenian -
                                        slovenščina
                                    </option>
                                    <option value="so" {{in_array('so', $config) ? 'selected' : ''}}>Somali -
                                        Soomaali
                                    </option>
                                    <option value="st" {{in_array('st', $config) ? 'selected' : ''}}>Southern
                                        Sotho
                                    </option>
                                    <option value="es" {{in_array('es', $config) ? 'selected' : ''}}>Spanish -
                                        español
                                    </option>
                                    <option value="su" {{in_array('su', $config) ? 'selected' : ''}}>Sundanese
                                    </option>
                                    <option value="sw" {{in_array('sw', $config) ? 'selected' : ''}}>Swahili -
                                        Kiswahili
                                    </option>
                                    <option value="sv" {{in_array('sv', $config) ? 'selected' : ''}}>Swedish -
                                        svenska
                                    </option>
                                    <option value="tg" {{in_array('tg', $config) ? 'selected' : ''}}>Tajik -
                                        тоҷикӣ
                                    </option>
                                    <option value="ta" {{in_array('ta', $config) ? 'selected' : ''}}>Tamil - தமிழ்
                                    </option>
                                    <option value="tt" {{in_array('tt', $config) ? 'selected' : ''}}>Tatar</option>
                                    <option value="te" {{in_array('te', $config) ? 'selected' : ''}}>Telugu -
                                        తెలుగు
                                    </option>
                                    <option value="th" {{in_array('th', $config) ? 'selected' : ''}}>Thai - ไทย
                                    </option>
                                    <option value="ti" {{in_array('ti', $config) ? 'selected' : ''}}>Tigrinya -
                                        ትግርኛ
                                    </option>
                                    <option value="to" {{in_array('to', $config) ? 'selected' : ''}}>Tongan - lea
                                        fakatonga
                                    </option>
                                    <option value="tr" {{in_array('tr', $config) ? 'selected' : ''}}>Turkish -
                                        Türkçe
                                    </option>
                                    <option value="tk" {{in_array('tk', $config) ? 'selected' : ''}}>Turkmen
                                    </option>
                                    <option value="tw" {{in_array('tw', $config) ? 'selected' : ''}}>Twi</option>
                                    <option value="uk" {{in_array('uk', $config) ? 'selected' : ''}}>Ukrainian -
                                        українська
                                    </option>
                                    <option value="ur" {{in_array('ur', $config) ? 'selected' : ''}}>Urdu - اردو
                                    </option>
                                    <option value="ug" {{in_array('ug', $config) ? 'selected' : ''}}>Uyghur</option>
                                    <option value="uz" {{in_array('uz', $config) ? 'selected' : ''}}>Uzbek -
                                        o‘zbek
                                    </option>
                                    <option value="vi" {{in_array('vi', $config) ? 'selected' : ''}}>Vietnamese -
                                        Tiếng Việt
                                    </option>
                                    <option value="wa" {{in_array('wa', $config) ? 'selected' : ''}}>Walloon - wa
                                    </option>
                                    <option value="cy" {{in_array('cy', $config) ? 'selected' : ''}}>Welsh -
                                        Cymraeg
                                    </option>
                                    <option value="fy" {{in_array('fy', $config) ? 'selected' : ''}}>Western
                                        Frisian
                                    </option>
                                    <option value="xh" {{in_array('xh', $config) ? 'selected' : ''}}>Xhosa</option>
                                    <option value="yi" {{in_array('yi', $config) ? 'selected' : ''}}>Yiddish
                                    </option>
                                    <option value="yo" {{in_array('yo', $config) ? 'selected' : ''}}>Yoruba - Èdè
                                        Yorùbá
                                    </option>
                                    <option value="zu" {{in_array('zu', $config) ? 'selected' : ''}}>Zulu -
                                        isiZulu
                                    </option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            @php($config=get_business_settings('logo', false))

                            <div class="form-group">
                                <label class="form-label">{{ translate('Logo') }} <span class="text-danger">* ( Ratio 300x100 )</span></label>
                                <input type='file' onchange="loadFile_logo(logo)"
                                       name="logo" id="logo"
                                       class="@error('logo') is-invalid @enderror"
                                       style="display:none;"/>
                                <button id="output_logo" type="button"
                                        onclick="document.getElementById('logo').click();"
                                        style="width: 300px;
                                                    height: 100px;
                                                    background-image: url({{ asset('storage/'.$config) }});
                                                    background-repeat: no-repeat;
                                                    background-size: cover;
                                                    background-position: center;
                                                    "/>
                            </div>

                            <script>
                                var loadFile_logo = function (website_logo) {
                                    var image = document.getElementById('output_logo');
                                    var src = URL.createObjectURL(event.target.files[0]);
                                    image.style.backgroundImage = 'url(' + src + ')';
                                };
                            </script>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            @php($config=get_business_settings('fav_icon', false))

                            <div class="form-group">
                                <label class="form-label">{{ translate('Fav Icon') }} <span class="text-danger">* ( Ratio 150x150 )</span></label>
                                <input type='file' onchange="loadFile_fav_icon(fav_icon)"
                                       name="fav_icon" id="fav_icon"
                                       class="@error('fav_icon') is-invalid @enderror"
                                       style="display:none;"/>
                                <button id="output_fav_icon" type="button"
                                        onclick="document.getElementById('fav_icon').click();"
                                        style="width: 150px;
                                                    height: 150px;
                                                    background-image: url({{ asset('storage/'.$config) }});
                                                    background-repeat: no-repeat;
                                                    background-size: cover;
                                                    background-position: center;
                                                    "/>
                            </div>

                            <script>
                                var loadFile_fav_icon = function (fav_icon) {
                                    var image = document.getElementById('output_fav_icon');
                                    var src = URL.createObjectURL(event.target.files[0]);
                                    image.style.backgroundImage = 'url(' + src + ')';
                                };
                            </script>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card mb-3">

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success mr-1 data-submit">{{ translate('Save') }}</button>
                </div>
            </div>

        </form>

    </div>
    <!-- / Content -->
@endsection

@section('js')

    <script>
        $(function () {
            $(document)
                .on("click", ".btn-add", function (e) {
                    e.preventDefault();
                    var controlForm = $("#myRepeatingFields:first"),
                        currentEntry = $(this).parents(".entry:first"),
                        newEntry = $(currentEntry.clone()).appendTo(controlForm);
                    newEntry.find("input").val("");
                    controlForm.find(".entry:not(:last) .btn-add").removeClass("btn-add").addClass("btn-remove")
                        .removeClass("btn-success").addClass("btn-danger").html(" {{ translate('Delete') }}");
                })
                .on("click", ".btn-remove", function (e) {
                    e.preventDefault();
                    $(this).parents(".entry:first").remove();
                    return false;
                })
        })
    </script>

    <script>
        $(document).ready(function () {
            var dropZone = $('#dropZone');

            dropZone.on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
            }).on('drop', function (e) {
                var files = e.originalEvent.dataTransfer.files;
                handleFiles(files);
            });

            $('#image').on('change', function () {
                var files = $(this)[0].files;
                handleFiles(files);
            });

            function handleFiles(files) {
                var image = document.getElementById('output_image');
                var src = URL.createObjectURL(files[0]);
                image.style.backgroundImage = 'url(' + src + ')';
            }
        });
    </script>
@stop

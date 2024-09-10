// const lngs = {
//     es: { nativeName: 'Español' },
//     en: { nativeName: 'Inglés' },
//     pt: { nativeName: 'Portugués' },
// };

const rerender = () => {
    // start localizing, details:
    // https://github.com/i18next/jquery-i18next#usage-of-selector-function
    $('body').localize();

    // $('title').text($.t('head.title'))
    // $('meta[name=description]').attr('content', $.t('head.description'))
}

$(function () {
    i18next.init({
        debug: true,
        fallbackLng: 'es',  
        resources: translation(),
    }, (err, t) => {
        if (err) return console.error(err);

        jqueryI18next.init(i18next, $, { useOptionsAttr: true });
        
        // fill language switcher
        // Object.keys(lngs).map((lng) => {
        //     const opt = new Option(lngs[lng].nativeName, lng);
        //     if (lng === i18next.resolvedLanguage) {
        //     opt.setAttribute("selected", "selected");
        //     }
        //     $('#languageSwitcher').append(opt);
        // });
        $('.languageSwitcher').change((a, b, c) => {
            const chosenLng = $(this).find("option:selected").attr('value');
            i18next.changeLanguage(chosenLng, () => {
            rerender();
            });
        });

        rerender();
    });

});
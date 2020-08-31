import '../css/app.scss';
import {observe} from 'selector-observer';
require('../../vendor/ninsuo/symfony-collection/jquery.collection');

observe('[data-prototype]', {
    add(el) {
        console.log(el);
        let prefix = $(el).attr('jquery_prefix');
        let submitOnChange = $(el).attr('auto_submit_on_collection_change');

        let min = $(el).attr('data-min') ?? 0;
        $(el).collection({
            prefix: prefix,
            min: min,
            allow_up: false,
            allow_down: false,
            // custom_add_location: true,
            add_at_the_end: true,
            // after_remove: function (collection, element) {
            //     if (submitOnChange) {
            //         let form = collection.closest('form');
            //         form.ajaxSubmit({
            //             target: form,
            //             // replaceTarget: true,
            //         });                }
            // },
            // after_add: function (collection) {
            //     if (submitOnChange) {
            //         let form = collection.closest('form');
            //         form.ajaxSubmit({
            //             target: form,
            //             // replaceTarget: true,
            //         });
            //     }
            //     return false;
            // },
            after_init: function (collection) {
                // console.log(collection);
            },
            add: `<span class="btn btn-success" ><i class="fa fa-plus-circle"></i> </span>`,
            remove: `<span class="btn btn-danger" ><i class="fa fa-minus-circle"></i> </span>`,
        });
    }
});

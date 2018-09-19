/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
({
    initialize: function(options) {
        this._super('initialize', [options]);
        var request = this.context.get('request');
        this.keys = request.keys;
        this.page_data = request.page_data[this.keys[0]];
    },

    /* RENDER index page
    *******************/
    _renderHtml: function() {
        var self = this,
            i = 0,
            html = '',
            chapter_key = this.keys[0],
            section_key = this.keys[1],
            section;

        if (section_key === 'index') {

            // home index call
            $.each(this.page_data.sections, function(kS, vS) {
                if (!vS.index) {
                    return;
                }

                html += (i % 3 === 0 ? '<div class="row-fluid">' : '');
                html += '<div class="span4"><h3>' +
                    '<a class="section-link" href="' +
                    (vS.url ? vS.url : self.fmtLink(kS)) + '">' +
                    vS.title + '</a></h3><p>' + vS.description + '</p><ul>';
                if (vS.pages) {
                    $.each(vS.pages, function(kP, vP) {
                        html += '<li ><a class="section-link" href="' +
                            (vP.url ? vP.url : self.fmtLink(kS, kP)) + '">' +
                            vP.title + '</a></li>';
                    });
                }
                html += '</ul></div>';
                html += (i % 3 === 2 ? '</div>' : '');

                i += 1;
            });

        } else {

            section = this.page_data.sections[section_key];

            // section index call
            $.each(section.pages, function(kP, vP) {
                html += (i % 4 === 0 ? '<div class="row-fluid">' : '');
                html += '<div class="span3"><h3>' +
                    (!vP.items ?
                        ('<a class="section-link" href="' +
                            (vP.url ? vP.url : self.fmtLink(section_key, kP)) + '">' +
                            vP.title + '</a>') :
                        vP.title
                    ) +
                    '</h3><p>' + vP.description;
                html += '</p></div>';
                html += (i % 4 === 3 ? '</div>' : '');

                i += 1;
            });
        }

        this._super('_renderHtml');

        this.$('#index-content').append('<section id="section-menu"></section>').html(html);
    },

    fmtLink: function(s, p) {
        return '#Styleguide/' + this.keys[0] + '/' + s + (p ? '-' + p : '-index');
    }

})

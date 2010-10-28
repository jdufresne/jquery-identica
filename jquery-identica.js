/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


(function($) {

$.identica = new Object();

$.identica.defaults = {
    'url': null,
    'limit': null
};

$.fn.identica = function(options) {
    options = $.extend({}, $.identica.defaults, options);

    var args = {
        'user': options.user,
        'limit': options.limit
    };

    var el = this;
    $.get(options.url, args, function(data) {
        var $ul = $('<ul />', {'id': 'identica-notices'});
        for (var notice in data) {
            $ul.append($('<li />').html(data[notice]));
        }
        $(el).html($ul);
    });
};

})(jQuery);

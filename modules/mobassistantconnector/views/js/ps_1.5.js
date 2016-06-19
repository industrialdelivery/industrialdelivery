/**
 *    This file is part of Mobile Assistant Connector.
 *
 *   Mobile Assistant Connector is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Mobile Assistant Connector is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Mobile Assistant Connector.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  @author    eMagicOne <contact@emagicone.com>
 *  @copyright 2014-2015 eMagicOne
 *  @license   http://www.gnu.org/licenses   GNU General Public License
 */

$(document).ready(function() {
    var mobassistantconnector_tracknum_message_lng_all_id = $('#mobassistantconnector_tracknum_message_lng_all').val();

    $('div.displayed_flag').attr('id', 'mobassistantconnector_lng_disable');

    if (mobassistantconnector_tracknum_message_lng_all_id > 0) {
        $('#mobassistantconnector_tracknum_message_lng_all').attr('checked', 'checked');
        changeFormLanguage(mobassistantconnector_tracknum_message_lng_all_id);
    }

    if ($('#mobassistantconnector_tracknum_message_lng_all').is(':checked')) {
        $('#mobassistantconnector_lng_disable').removeClass('displayed_flag');
    }

    $('#mobassistantconnector_tracknum_message_lng_all'). click(function() {
        if ($(this).is(':checked')) {
            $(this).val(id_language);
            $('#mobassistantconnector_lng_disable').removeClass('displayed_flag');
        } else {
            $('#mobassistantconnector_lng_disable').addClass('displayed_flag');
        }
    });
});
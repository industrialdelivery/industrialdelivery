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
    var baseUrl          = $('#mobassistantconnector_base_url').val();
    var loader_img       = '<img src="' + baseUrl + 'modules/mobassistantconnector/views/img/loader.gif">';
    var loader           = '<div class="mobassistantconnector_loader">' + loader_img + '</div>';
    var loader_devices   = '<div id="mobassistantconnector_loader_diveces">' + loader + '</div>';
    var loader_qrcode    = '<div id="mobassistantconnector_loader_qrcode">' + loader + '</div>';
    var loader_container = '<div id="mobassistantconnector_loader_container">' + loader + '</div>';
    var devices_parent   = $('#mobassistantconnector_devices').parent();
    var qrcode_parent    = $('#mobassistantconnector_qrcode').parent();
    var key              = $('#mobassistantconnector_key').val();

    devices_parent.append(loader_devices);
    qrcode_parent.append(loader_qrcode);

    var password           = $('#mobassistantconnector_password');
    var login              = $('#mobassistantconnector_login');
    var password_value_old = password.val();
    var login_value_old    = login.val();
    var yes                = '<img src="' + baseUrl + 'modules/mobassistantconnector/views/img/yes.png">';
    var no                 = '<img src="' + baseUrl + 'modules/mobassistantconnector/views/img/no.png">';
    var enabled            = '<img src="' + baseUrl + 'modules/mobassistantconnector/views/img/enabled.gif" title="Disable">';
    var disabled           = '<img src="' + baseUrl + 'modules/mobassistantconnector/views/img/disabled.gif" title="Enable">';

    var mobassistantconnector_div_qrcode = '<div id="mobassistantconnector_div_qrcode" style="position: relative;">' +
        '<div id="mobassistantconnector_div_qrcode_img"></div>' +
        '<div id="mobassistantconnector_div_qrcode_changed" style="display: none; position: absolute; color: red; z-index: 1000; top: 100px; left: 15px; width: 230px;">' +
        'Login details have been changed. Save changes for code to be regenerated</div></div>';
    var mobassistantconnector_devices_container = '<div id="mobassistantconnector_devices_container">' + loader_container + '</div>';
    var mobassistantconnector_devices_table_div = '<div id="mobassistantconnector_devices_table_div"></div>';

    qrcode_parent.append(mobassistantconnector_div_qrcode);
    devices_parent.append(mobassistantconnector_devices_container);
    $('#mobassistantconnector_devices_container').append(mobassistantconnector_devices_table_div);

    var qrcode = new QRCode(document.getElementById('mobassistantconnector_div_qrcode_img'), {
        width : 250,
        height : 250
    });
    $('#mobassistantconnector_loader_qrcode').hide();
    qrcode.makeCode($('#mobassistantconnector_qr_data').val());

    $('#mobassistantconnector_div_qrcode').parent().append('<p class="preference_description help-block">' + $('#mobassistantconnector_qr_description').val() + '</p>');

    login.keyup(function() {
        changeQRCode();
    });

    password.keyup(function() {
        changeQRCode();
    });

    getDevices();

    setEvents();

    function changeQRCode() {
        var login_new      = login.val();
        var password_new   = password.val();
        var qrcode_changed = $('#mobassistantconnector_div_qrcode_changed');
        var qrcode_img     = $('#mobassistantconnector_div_qrcode_img');

        if (login_value_old != login_new || password_value_old != password_new) {
            $(qrcode_changed).show('fast');
            $(qrcode_img).css('opacity', '0.1');
        } else {
            $(qrcode_changed).hide('fast');
            $(qrcode_img).css('opacity', '1');
        }
    }

    function setEvents() {
        $('div.mobassistantconnector_status').live('click', function() {
            changeStatus($(this));
        });

        $('div.mobassistantconnector_delete').live('click', function() {
            if (confirm('Are you sure you want to delete device?')) {
                deleteDeviceBulk($(this).attr('device_id'));
            }
        });

        $('#mobassistantconnector_bulk_select_all').live('click', function() {
            $('.mobassistantconnector_pushes').each(function() {
                $(this).prop('checked', true);
            });
        });

        $('#mobassistantconnector_bulk_unselect_all').live('click', function() {
            $('.mobassistantconnector_pushes').each(function() {
                $(this).prop('checked', false);
            });
        });

        $('#mobassistantconnector_bulk_status_active').live('click', function() {
            changeStatusBulk(1);
        });

        $('#mobassistantconnector_bulk_status_inactive').live('click', function() {
            changeStatusBulk(0);
        });

        $('#mobassistantconnector_bulk_delete').live('click', function() {
            if (confirm('Are you sure you want to delete selected devices?')) {
                deleteDeviceBulk(false);
            }
        });

        $(document).on('click', function(e) {
            var bulk_list = $('#mobassistantconnector_bulk_list');

            if (!bulk_list.is(':visible') && e.target.hasAttribute('id')
                && (e.target.id == 'mobassistantconnector_bulk_btn' || e.target.id == 'mobassistantconnector_bulk_btn_img')) {
                bulk_list.show();
            } else if (bulk_list.is(':visible')) {
                bulk_list.hide();
            }
        });
    }

    function getDevices() {
        $.post(baseUrl + 'modules/mobassistantconnector/functions/ajax.php',
            {
                call_function: 'getDevices',
                key: key
            },
            function (data) {
                var target      = $('#mobassistantconnector_devices_table_div');
                var loader_dev  = $('#mobassistantconnector_loader_diveces');
                var loader_bulk = $('#mobassistantconnector_loader_container');

                if (data != 'error' && data != 'Authentication error') {
                    var is_data_empty = true;
                    var order;
                    var customer;
                    var status;
                    var table_data = '<table id="mobassistantconnector_devices_table" class="table product">' +
                        '<tr><th>Device Name</th><th>Account Email</th><th>Last Activity</th><th></th><th>App Connection ID</th><th>Shop</th>' +
                        '<th>New Order</th><th>New Customer</th><th>Order Statuses</th><th>Currency</th><th>Status</th><th></th></tr>';

                    $.each(data, function(index, values) {
                        is_data_empty = false;
                        var count_pushes = values.pushes.length;

                        for (var i = 0; i < count_pushes; i++) {
                            var delimiter = '';

                            if (i == (count_pushes - 1)) {
                                delimiter = ' class="mobassistantconnector_device_delimiter" ';
                            }

                            table_data += '<tr id="mobassistantconnector_row_device_' + values.pushes[i].id + '"' + delimiter + '>';

                            if (i == 0) {
                                table_data += '<td class="mobassistantconnector_device_delimiter" rowspan="' + count_pushes + '">' +
                                    values.device_name + '</td><td class="mobassistantconnector_device_delimiter" rowspan="' + count_pushes + '">' +
                                    values.account_email + '</td><td class="mobassistantconnector_device_delimiter mobassistantconnector_center" ' +
                                    'rowspan="' + count_pushes + '">' + values.last_activity + '</td>';
                            }

                            if (values.pushes[i].new_order == 1) {
                                order = yes;
                            } else {
                                order = no;
                            }

                            if (values.pushes[i].new_customer == 1) {
                                customer = yes;
                            } else {
                                customer = no;
                            }

                            if (values.pushes[i].status == 1) {
                                status = '<div class="mobassistantconnector_status" device_id="' + values.pushes[i].id + '" val="1">' + enabled + '</div>';
                            } else {
                                status = '<div class="mobassistantconnector_status" device_id="' + values.pushes[i].id + '" val="0">' + disabled + '</div>';
                            }

                            table_data += '<td><input type="checkbox" class="mobassistantconnector_pushes" name="mobassistantconnector_pushes[]" ' +
                                'value="' + values.pushes[i].id +'"></td><td class="mobassistantconnector_center">' +
                                values.pushes[i].app_connection_id + '</td><td>' + values.pushes[i].shop +
                                '</td><td class="mobassistantconnector_center">' + order +
                                '</td><td class="mobassistantconnector_center">' + customer +
                                '</td><td>' + values.pushes[i].order_statuses + '</td><td class="mobassistantconnector_center">' +
                                values.pushes[i].currency_iso + '</td><td class="mobassistantconnector_center mobassistantconnector_status">' +
                                status + '</td><td><div class="mobassistantconnector_delete" device_id="' + values.pushes[i].id + '"><img src="' +
                                baseUrl + 'modules/mobassistantconnector/views/img/trash.png" title="Delete"></div></td></tr>';
                        }
                    });

                    if (is_data_empty) {
                        table_data += '<tr><td colspan="12" style="text-align: center">No data</td></tr>';
                    }

                    table_data += '</table><div style="margin-top: 10px; margin-bottom: 10px"><button type="button" ' +
                        'id="mobassistantconnector_bulk_btn">Bulk actions <img id="mobassistantconnector_bulk_btn_img" src="' + baseUrl +
                        'modules/mobassistantconnector/views/img/down.png"></button></div><div id="mobassistantconnector_bulk_list">' +
                        '<table><tr id="mobassistantconnector_bulk_select_all"><td><img src="' + baseUrl +
                        'modules/mobassistantconnector/views/img/checked.png"></td><td>Select all</td></tr>' +
                        '<tr id="mobassistantconnector_bulk_unselect_all"><td><img src="' + baseUrl +
                        'modules/mobassistantconnector/views/img/unchecked.png"></td><td>Unselect all</td>' +
                        '</tr><tr class="mobassistantconnector_delimiter"><td colspan="2"><hr></td></tr>' +
                        '<tr id="mobassistantconnector_bulk_status_active"><td><img width="12px" height="12px" src="' + baseUrl +
                        'modules/mobassistantconnector/views/img/enabled.gif"></td><td>Change status to active</td>' +
                        '</tr><tr id="mobassistantconnector_bulk_status_inactive"><td><img width="12px" height="12px" src="' + baseUrl +
                        'modules/mobassistantconnector/views/img/disabled.gif"></td><td>Change status to inactive</td></tr>' +
                        '<tr class="mobassistantconnector_delimiter"><td colspan="2"><hr></td></tr>' +
                        '<tr id="mobassistantconnector_bulk_delete"><td><img src="' + baseUrl +
                        'modules/mobassistantconnector/views/img/bulk_trash.png"></td><td>Delete selected</td></tr></table>';
                    loader_dev.hide();
                    loader_bulk.hide();
                    target.html(table_data);
                } else {
                    loader_bulk.hide();
                    loader_dev.hide();
                    setButtonActive(true);
                    alert(data);
                }
            }, 'json'
        );
    }

    function changeStatus(selector) {
        var val      = selector.attr('val');
        var img_prev = selector.html();
        selector.html(loader_img);

        if (val == 0) {
            val = 1;
        } else {
            val = 0;
        }

        $.post(baseUrl + 'modules/mobassistantconnector/functions/ajax.php',
            {
                call_function: 'changeStatus',
                push_ids: selector.attr('device_id'),
                value: val,
                key: key
            },
            function(data) {
                if (data == 'success') {
                    if (val == 1) {
                        selector.html(enabled);
                        selector.attr('val', 1);
                    } else {
                        selector.html(disabled);
                        selector.attr('val', 0)
                    }
                } else {
                    selector.html(img_prev);
                    alert(data);
                }
            }, 'json'
        ).error(function() {
                selector.html(img_prev);
                alert('Some error occurred');
            });
    }

    function changeStatusBulk(val) {
        var push_ids = getPushIds();

        if (push_ids.length == 0) {
            alert('Nothing selected');
            return;
        }

        var loader_bulk = $('#mobassistantconnector_loader_container');

        setButtonActive(false);
        loader_bulk.show();
        $('#mobassistantconnector_devices_table').addClass('mobassistantconnector_inactive');
        $.post(baseUrl + 'modules/mobassistantconnector/functions/ajax.php',
            {
                call_function: 'changeStatus',
                push_ids: push_ids,
                value: val,
                key: key
            },
            function(data) {
                if (data != 'success') {
                    alert(data);
                }

                getDevices();
            }, 'json'
        ).error(function() {
                loader_bulk.hide();
                setButtonActive(true);
                alert('Some error occurred');
            });
    }

    function deleteDeviceBulk(id) {
        var push_ids;

        if (!id) {
            push_ids = getPushIds();
        } else {
            push_ids = id;
        }

        if (push_ids.length == 0) {
            alert('Nothing selected');
            return;
        }

        var loader_bulk = $('#mobassistantconnector_loader_container');

        setButtonActive(false);
        loader_bulk.show();
        $('#mobassistantconnector_devices_table').addClass('mobassistantconnector_inactive');
        $.post(baseUrl + 'modules/mobassistantconnector/functions/ajax.php',
            {
                call_function: 'deleteDevice',
                push_ids: push_ids,
                key: key
            },
            function(data) {
                if (data != 'success') {
                    alert(data);
                }

                getDevices();
            }, 'json'
        ).error(function() {
                loader_bulk.hide();
                setButtonActive(true);
                alert('Some error occurred');
            });
    }

    function getPushIds() {
        var push_ids = '';

        $('[name="mobassistantconnector_pushes[]"]:checked').each(function() {
            if (push_ids.length > 0) {
                push_ids += ',' + $(this).val();
            } else {
                push_ids = $(this).val();
            }
        });

        return push_ids;
    }

    function setButtonActive(val) {
        var button = $('#mobassistantconnector_bulk_btn');

        if (val) {
            button.removeAttr('disabled');
        } else {
            button.attr('disabled','disabled');
        }
    }
});
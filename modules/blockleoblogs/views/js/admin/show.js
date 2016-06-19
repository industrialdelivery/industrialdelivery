/**
 * Owl carousel
 *
 * @copyright Commercial License By LeoTheme.Com 
 * @email leotheme.com
 * @visit http://www.leotheme.com
 */
 $(document).ready(function(){
    // Check type of Carousel type - BEGIN
    $('.form-action').change(function(){
        elementName = $(this).attr('name');
        $('.'+elementName+'_sub').hide(300);
        $('.'+elementName+'-'+$(this).val()).show(500);
    });
    $('.form-action').trigger("change");
    // Check type of Carousel type - END
    
    $("#module_form").validate({
        rules : {
                owl_items : {
                    min : 1,
                },
                owl_rows : {
                    min : 1,
                }
            }        
    });
 });
 
$.validator.addMethod("owl_items_custom", function(value, element) {
    pattern_en = /^\[\[[0-9]+, [0-9]+\](, [\[[0-9]+, [0-9]+\])*\]$/;  // [[320, 1], [360, 1]]
    pattern_dis = /^0?$/
    //console.clear();
    //console.log (pattern.test(value));
    return (pattern_en.test(value) || pattern_dis.test(value));
    //return false;
}, "Please enter correctly config follow under example.");

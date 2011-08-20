/**
 * Created with Visual Form Builder by 23rd and Walnut
 * www.visualformbuilder.com
 * www.23andwalnut.com
 */


jQuery(document).ready(function($)
{
    //Style selects, checkboxes, etc
    $("select, input:checkbox, input:radio, input:file").uniform();

    //Date and Range Inputs
	

    /**
     * Get the jQuery Tools Validator to validate checkbox and
     * radio groups rather than each individual input
     */

    $('[type=checkbox]').bind('change', function(){
        clearCheckboxError($(this));
    });


    //validate checkbox and radio groups
    function validateCheckRadio(){
        var err = {};

        $('.radio-group, .checkbox-group').each(function(){
             if($(this).hasClass('required'))
                if (!$(this).find('input:checked').length)
                    err[$(this).find('input:first').attr('name')] = 'Please complete this mandatory field.';
        });

        if (!$.isEmptyObject(err)){
            validator.invalidate(err);
            return false
        }
        else return true;

    }





    //clear any checkbox errors
    function clearCheckboxError(input){
        var parentDiv = input.parents('.field');

        if (parentDiv.hasClass('required'))
            if (parentDiv.find('input:checked').length > 0){
                validator.reset(parentDiv.find('input:first'));
                parentDiv.find('.error').remove();
            }
    }




    //Position the error messages next to input labels
    $.tools.validator.addEffect("labelMate", function(errors, event){
        $.each(errors, function(index, error){
            error.input.first().parents('.field').find('.error').remove().end().find('label').after('<span class="error">' + error.messages[0] + '</span>');
        });

    }, function(inputs){
        inputs.each(function(){
            $(this).parents('.field').find('.error').remove();
        });

    });


    /**
     * Handle the form submission, display success message if
     * no errors are returned by the server. Call validator.invalidate
     * otherwise.
     */

    $(".TTWForm").validator({effect:'labelMate'}).submit(function(e){
       var form = $(this), checkRadioValidation = validateCheckRadio();

        if(!e.isDefaultPrevented() && checkRadioValidation){
            $.post(form.attr('action'), form.serialize(), function(data){
                data = $.parseJSON(data);

                if(data.status == 'success'){
                    form.fadeOut('fast', function(){
                        $('.TTWForm-container').append('<h2 class="success-message">Success!</h2>');
                    });
                }
                else validator.invalidate(data.errors);

            });
        }

        return false;
    });

    var validator = $('.TTWForm').data('validator');


});

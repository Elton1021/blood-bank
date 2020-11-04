//sets rules
function setRules(rules){
    //variables to log error and keep count
    const INPUT_ERROR_LOG = {}
    let errCount = 0
    let ajaxValidationCount = 0

    //mark invalid inputs and logs the error on client side
    function markInvalid (id){
        $('#'+id).removeClass('is-valid')
        $('#'+id).addClass('is-invalid')
        $('#'+id).siblings('small:not(".valid-feedback")').addClass('invalid-feedback')
        if(typeof(INPUT_ERROR_LOG[id]) === "undefined" || !INPUT_ERROR_LOG[id]){
            errCount++
        }
        INPUT_ERROR_LOG[id] = true;
    }

    function markValid(id){
        if(INPUT_ERROR_LOG[id]){
            INPUT_ERROR_LOG[id] = false;
            errCount--
        }
        if($('#'+id).val().trim().length > 0){
            //if valid then it's marked valid and logs the error on client side
            $('#'+id).removeClass('is-invalid')
            $('#'+id).addClass('is-valid')
        }  else {
            //in case the field is not required and first attempt didn't meet the rule everything is marked normal
            $('#'+id).siblings('small:not(".valid-feedback")').removeClass('invalid-feedback')
            $('#'+id).removeClass('is-invalid')
            $('#'+id).removeClass('is-valid')
        }
    }

    function validation (id) {
        const value = $('#'+id).val().trim();
        //checks if the rules are met
        if(typeof(rules[id]) !== "undefined" && (
            typeof(rules[id].regex) !== "undefined" && rules[id].regex.test(value) && ( typeof(rules[id].matchRegex) === "undefined" ||  typeof(rules[id].matchRegex) !== "undefined" && rules[id].matchRegex) ||
            typeof(rules[id].regex) !== "undefined" && !rules[id].regex.test(value) && typeof(rules[id].matchRegex) !== "undefined" && !rules[id].matchRegex ||
            typeof(rules[id].maxLength) !== "undefined" && rules[id].maxLength < value.length ||
            typeof(rules[id].minLength) !== "undefined" && rules[id].minLength > value.length ||
            typeof(rules[id].matchIdValue) !== "undefined" && $('#'+rules[id].matchIdValue).val() !== value ||
            $('#'+id).prop('required') && value.length === 0
        )){
            markInvalid(id)
        } else {
            markValid(id)
        }
    }

    //loops through rules creating event listeners for each rules using jQuery
    Object.keys(rules).forEach(id => {
        $('#'+id).on('keyup',e => validation(id))
        if(typeof(rules[id].ajax) !== "undefined"){
            $('#'+id).on('blur',e => {
                data = typeof(rules[id].ajax.data) != "undefined" ? {...rules[id].ajax.data} : {}
                data[rules[id].ajax.dataName] = $('#'+id).val().trim();
                $.ajax({
                    ...rules[id].ajax,
                    data,
                    success: (res) => {
                        if(rules[id].ajax.success(res)){
                            markInvalid(id)
                        } else {
                            markValid(id)
                        }
                    }
                })
            })
        }               
    })

    //form validation on submit (precaution)
    $('form').on('submit', e => {
        $('input[required], textarea[required], select[required]').each((_, item) => {
            validation($(item).prop('id'),true)
        })
        if(errCount > 0){
            e.preventDefault()
        }
    })
}
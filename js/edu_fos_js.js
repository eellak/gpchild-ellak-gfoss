function edu_fos_set_selected_values(){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == 'thematiki') 
        {
            jQuery('#thematiki-select option[value='+sParameterName[1]+']').attr('selected', 'selected');
        }
        else if (sParameterName[0] == 'vathmida') 
        {
            jQuery('#vathmida-select option[value='+sParameterName[1]+']').attr('selected', 'selected');
        }
        else if (sParameterName[0] == 'antikimeno') 
        {
            jQuery('#antikimeno-select option[value='+sParameterName[1]+']').attr('selected', 'selected');
        }
    }
}

jQuery(document).ready(edu_fos_set_selected_values());
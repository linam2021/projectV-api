function oncheck(checkboxElem) {
    if(checkboxElem.checked) {
      checkboxElem.parentElement.childNodes[3].value=checkboxElem.parentElement.childNodes[1].value; //questionBankID
      checkboxElem.parentElement.nextElementSibling.childNodes[3].value=checkboxElem.parentElement.nextElementSibling.childNodes[1].innerHTML; //course_name
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.childNodes[3].value=checkboxElem.parentElement.nextElementSibling.nextElementSibling.childNodes[1].innerHTML; //course_link
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[1].required=true;
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[1].required=true;
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[1].disabled = false;
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[1].disabled = false;
    } 
    else {
      checkboxElem.parentElement.childNodes[3].value="";//questionBankID
      checkboxElem.parentElement.nextElementSibling.childNodes[3].value="";//course_name
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.childNodes[3].value=""; //course_link
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[1].required=false;
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[1].value=null;
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[1].required=false;
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[1].value="";
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[1].disabled = true;
      checkboxElem.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.childNodes[1].disabled = true;
    }
  }
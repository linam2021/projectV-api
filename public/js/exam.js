function showHideElement() {
    var x = document.getElementById("exam_type").value;
    let questions_count_label = document.getElementById("questions_count_label");
    let exam_duration_label = document.getElementById("exam_duration_label");
    let exam_duration =document.getElementById("exam_duration");
    let questions_count =document.getElementById("questions_count");
    let exam_date_label =document.getElementById("exam_date_label");
    if (x=="theoretical")
    {  
      questions_count_label.removeAttribute("hidden");
      exam_duration_label.removeAttribute("hidden");
      exam_duration.setAttribute('type','text'); 
      questions_count.setAttribute('type','text'); 
      exam_date_label.innerHTML ="تاريخ الامتحان";
    }else {
      questions_count_label.setAttribute("hidden", "hidden");
      exam_duration_label.setAttribute("hidden", "hidden");
      exam_duration.setAttribute('type','hidden');
      questions_count.setAttribute('type','hidden');
      exam_date_label.innerHTML ="تاريخ الامتحان العملي";
    }
  }
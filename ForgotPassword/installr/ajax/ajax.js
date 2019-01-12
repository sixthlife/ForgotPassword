      var XMLHttpRequestObject = false; 

      var XMLHttpRequestObject = false; 

      try { 
        XMLHttpRequestObject = new ActiveXObject("MSXML2.XMLHTTP"); 
         } catch (exception1) { 
         try { 
           XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP"); 
         } catch (exception2) { 
           XMLHttpRequestObject = false; 
       } 
     } 

     if (!XMLHttpRequestObject && window.XMLHttpRequest) { 
       XMLHttpRequestObject = new XMLHttpRequest(); 
     } 

      function getData(dataSource, divID) 
      { 
        if(XMLHttpRequestObject) {
          var obj = document.getElementById(divID); 
          XMLHttpRequestObject.open("GET", dataSource); 

          XMLHttpRequestObject.onreadystatechange = function() 
          { 
            if (XMLHttpRequestObject.readyState == 4 && 
              XMLHttpRequestObject.status == 200) { 
                obj.innerHTML = XMLHttpRequestObject.responseText; 
            } 
          } 

          XMLHttpRequestObject.send(null); 
        }
      }
      
      function checkParam(varName, dbColumn, divID ){
		flag = true;
        if(XMLHttpRequestObject) {
          var obj = document.getElementById(divID); 
          XMLHttpRequestObject.open("GET", 'ajax/ajax.php?varName='+varName.value+'&dbColumn='+dbColumn); 

          XMLHttpRequestObject.onreadystatechange = function() 
          { 
          	 // alert(XMLHttpRequestObject.status);
            if (XMLHttpRequestObject.readyState == 4 && 
              XMLHttpRequestObject.status == 200) { 
				if(XMLHttpRequestObject.responseText!=""){		flag = true;
                obj.innerHTML = '<td><div class="red_up"><img src="images/blue_up-red.png" alt="" /></div><div class="red_middle"><img src="images/cross.jpg" alt="" /><strong>Error:</strong><p>'+XMLHttpRequestObject.responseText+'</p></div><div class=\"red_dwon\"><img src=\"images/blue_down-red.png\" alt=\"\" /></div></td>';              }
            } 
          } 

          XMLHttpRequestObject.send(null); 
        } 
		return flag; 
}
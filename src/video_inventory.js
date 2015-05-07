var category = {
  val: "All Movies"
};

function deleteVid(vid) {
  var parentId = vid.parentNode.parentNode;
  parentId = parentId.id;

  req = new XMLHttpRequest(); 

  if(!req)
    throw "Unable to create the Http Request"; 

  req.open("POST", "dbTest.php");
  req.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
  req.send("action=deleteId&id=" + parentId); 
  
  req.onreadystatechange = function() {
    if(this.readyState === 4) {
      printAll();
    }
  }
}

function checkoutVid(vid) {
  var parentId = vid.parentNode.parentNode;
  parentId = parentId.id;

  req = new XMLHttpRequest(); 

  if(!req)
    throw "Unable to create the Http Request"; 

  req.open("POST", "dbTest.php");
  req.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
  req.send("action=checkoutVid&id=" + parentId); 

  req.onreadystatechange = function() {
    if(this.readyState === 4) {
      printAll();
    }
  }
}

function validateAdd() {
  var name = document.getElementById("name_input").value; 
  var cat = document.getElementById("category_input").value; 
  var len = document.getElementById("length_input").value; 
  
  if (name === null || name === "") {
    alert("Name must be present"); 
    return false;
  }
   
  if (name.length > 255) {
    alert("Name must not be larger than 255 charactes.");
    return false; 
  }

  if (cat.length > 255) {
    alert("Category must not be larger than 255 charactes.");
    return false; 
  }

  if (/\D/.test(len)) {
    alert("Length must be a positive integer. ");
    return false; 
  }

  return true; 
}

function addVideo() {
  if (validateAdd()) { 

    var name = document.getElementById("name_input").value; 
    var cat = document.getElementById("category_input").value; 
    var len = document.getElementById("length_input").value; 

    req = new XMLHttpRequest(); 

    if(!req)
      throw "Unable to create the Http Request"; 

    req.open("POST", "dbTest.php");
    req.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
    req.send("action=addVideo&name_input=" + name +"&category_input=" + cat + "&length_input=" + len); 

    req.onreadystatechange = function() {
      if(this.readyState === 4) {
        printAll();
      }
    }
  }
}

function getCategories() {
  req = new XMLHttpRequest(); 

  if(!req)
    throw "Unable to create the Http Request"; 

  req.open("POST", "dbTest.php");
  req.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
  req.send("action=printCategories"); 

  req.onreadystatechange = function() {
    if(this.readyState === 4) {
      document.getElementById("category_holder").innerHTML = this.responseText;
    }
  }
}

function filterCategory() {
  var selection = document.getElementById("Category"); 
  category.val = selection.options[selection.selectedIndex].value; 
  printAll(); 
}

function deleteTable() {
  var div = document.getElementById("table_holder");

  for (var k = div.childNodes.length - 1; k >= 0; k--) {
    div.removeChild(div.childNodes[k]);
  }
}

function deleteAll() {
  req = new XMLHttpRequest(); 

  if(!req)
    throw "Unable to create the Http Request"; 

  req.open("POST", "dbTest.php");
  req.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
  req.send("action=deleteAll"); 

  req.onreadystatechange = function() {
    if(this.readyState === 4) {
      printAll(); 
    }
  }
}

function printAll() {
  if (document.getElementById("table_holder") != null)
    deleteTable(); 

  getCategories(); 

  req = new XMLHttpRequest(); 

  if(!req)
    throw "Unable to create the Http Request"; 

  req.open("POST", "dbTest.php");
  req.setRequestHeader("Content-type","application/x-www-form-urlencoded"); 
  req.send("action=printAll&cat=" + category.val); 

  req.onreadystatechange = function() {
    if(this.readyState === 4) {
      document.getElementById("table_holder").innerHTML = this.responseText;
    }
  }
}

window.onload = function () {
  printAll(); 
}
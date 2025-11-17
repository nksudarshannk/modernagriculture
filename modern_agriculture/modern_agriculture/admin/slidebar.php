 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<style>
    .toggle-btn {
    background-color: red; 
    color: #fff;  
    border: none;  
    padding: 5px 15px; 
    border-radius: 3px;  
    font-size: 20px; 
    font-weight: bold; 
    cursor: pointer;  
    transition: all 0.3s ease-in-out; 
  
}

.toggle-btn:hover {
    background-color:orange; 
     border: none;  
    transform: scale(1.1);  
   
}



</style>
<div class="top-header">
    <button class="toggle-btn" id="toggle-btn">☰</button>
    <div class="header-title">Farmer Dashboard</div>
   <button class="logout-btn" onclick="window.location.href='logout'">Logout</button>

</div>

<div class="sidebar" id="sidebar">
    <a href="dashboard" class="active">Dashboard</a>
    <a href="product">Products</a>
    <a href="users">Users</a>
    <a href="orders">orders</a>
    <a href="truncate">Clear</a>
    <a href="logout">Logout</a>
</div>



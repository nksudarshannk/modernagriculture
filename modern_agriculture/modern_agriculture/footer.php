<div class="footer text-center py-3">
     <p>&copy; <span id="currentYear"></span> All rights reserved. Blockchain In Agriculture</p>
</div>
  
<script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>
<style>
    .footer {
        background: linear-gradient(90deg, #1fb6ff 0%, #4fc08d 100%);
        color: white;
        width: 100%;
        bottom: 0;
        left: 0;
        margin-top: 50px;
        box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
    }

    #currentYear {
        font-weight: bold;
    }
</style>
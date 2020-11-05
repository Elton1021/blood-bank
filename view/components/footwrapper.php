        <footer class="footer bg-dark" style="z-index:99999;">
            <div class="container">
                <span class="text-muted">Designed & Developed By <span class="text-white">Elton Andrew</span>.</span>
            </div>
        </footer>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script>
        //to avoid overlapping nav bar in mobile view
        $('.navbar-toggler').on('click', () => {
            if(!$('#navbarNav').hasClass('show')){
                $('#toast-container').css({'margin-top':'255px'});
            } else {
                $('#toast-container').css({'margin-top':'50px'});
            }
        });
    </script>
</body>
</html>

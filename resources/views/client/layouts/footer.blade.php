<footer class="footer footer-v1">
    <div class="content-block footer-main">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="footer-nav inline-nav">
                        <ul>
                            <li>
                                <a href="{{ route('kitchen') }}" data-title="Кухні"> Кухні </a>
                            </li>
                            <li>
                                <a href="{{ route('hall') }}" data-title="Шафи/Передпокій"> Шафи / Передпокій </a>
                            </li>
                            <li>
                                <a href="{{ route('commercial') }}" data-title="Офісні/Торгові меблі"> Офісні / Торгові меблі </a>
                            </li>
                            <li>
                                <a href="{{ route('wardrobe') }}" data-title="Гардероби/Тумби"> Гардероби / Тумби </a>
                            </li>
                            <li>
                                <a href="{{ route('children') }}" data-title="Дитячі меблі"> Дитячі меблі </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    <ul class="social-network with-text">
                        <li><a target="_blank" href="https://www.facebook.com/Mebelnyymaster"><span class="icon-facebook"></span></a></li>
                        <li><a target="_blank" href="https://www.instagram.com/mebelnyymaster"><span class="icon-instagram"></span> </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom text-center">
        <div class="container">
            <p>© <b>Меблевий майстер {{ date('Y') }}</b>. Усі права захищено.</p>
        </div>
    </div>
</footer>

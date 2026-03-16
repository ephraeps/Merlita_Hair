<footer class="modern-footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>Points de vente</h3>
            <ul class="locations">
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>Matadi</strong><br>
                </li>
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>Mbanza Ngungu</strong><br>
                </li>
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>Kinshasa</strong><br>
                </li>
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>Kisantu</strong><br>
                </li>
            </ul>                
            </ul>
        </div>

        <div class="footer-section">
            <h3>Réseaux sociaux</h3>
            <div class="social-links">
                <a href="https://www.instagram.com/merlita_hair" target="_blank" class="social-btn instagram">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
                <a href="https://www.tiktok.com/@merlita_hair" target="_blank" class="social-btn tiktok">
                    <i class="fab fa-tiktok"></i> TikTok
                </a>
                <a href="https://whatsapp.com/channel/0029Vamm867GZNCsEqUDWS1w" target="_blank" class="social-btn whatsapp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
            </div>
        </div>

        <div class="footer-section">
            <h3>Informations</h3>
            <p>Ouvert du lundi au samedi<br>9h00 - 18h00</p>
            <p>Email: support@merlitahair.com</p>
            <p>Tél: +243844595413</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2026 Merlita Hair. Tous droits réservés.</p>
    </div>
</footer>

<style>
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

    .modern-footer {
        background-color: #1a1a1a;
        color: #ffffff;
        padding: 40px 0 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .footer-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .footer-section {
        margin-bottom: 25px;
    }

    .footer-section h3 {
        color: #dfd085;
        font-size: 1.2rem;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-section h3::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 2px;
        background-color: #dfd085;
    }

    .locations li {
        margin-bottom: 15px;
        list-style: none;
        padding-left: 0;
    }

    .locations i {
        color: #dfd085;
        margin-right: 10px;
    }

    .social-links {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .social-btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        width: fit-content;
    }

    .social-btn i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .instagram { background-color: #E1306C; }
    .tiktok { background-color: #000000; }
    .whatsapp { background-color: #25D366; }

    .social-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .footer-bottom {
        text-align: center;
        padding-top: 30px;
        margin-top: 30px;
        border-top: 1px solid #333;
        font-size: 0.9rem;
        color: #aaa;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .footer-container {
            grid-template-columns: 1fr;
        }
        
        .footer-section {
            text-align: center;
        }
        
        .footer-section h3::after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .social-links {
            align-items: center;
        }
    }
</style>
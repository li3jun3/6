// 轮播图功能
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[index].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }

    // 自动轮播
    setInterval(nextSlide, 5000);

    // 按钮点击事件
    prevBtn.addEventListener('click', prevSlide);
    nextBtn.addEventListener('click', nextSlide);

    // 移动端导航栏
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    hamburger.addEventListener('click', () => {
        navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
        hamburger.classList.toggle('active');
    });

    // 点击导航链接时关闭菜单
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.style.display = 'none';
            hamburger.classList.remove('active');
        });
    });

    // 滚动时改变导航栏样式
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            navbar.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
        } else {
            navbar.style.background = 'var(--white)';
            navbar.style.boxShadow = 'none';
        }
    });

    // 平滑滚动
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // 表单提交处理
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // 这里添加表单提交逻辑
            alert('感谢您的留言，我们会尽快回复！');
            this.reset();
        });
    }

    // 初始化仪表盘
    initGauges();

    // 初始化轮播图
    const swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });

    // 初始化图片放大轮播图
    const imageSwiper = new Swiper('.image-swiper', {
        loop: true,
        pagination: {
            el: '.image-swiper .swiper-pagination',
            clickable: true,
        },
    });

    // 图片点击放大
    document.querySelectorAll('.slide-image, .floorplan-image, .view-image').forEach(img => {
        img.addEventListener('click', function() {
            const modal = document.querySelector('.image-modal');
            const modalImg = modal.querySelector('img');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // 同步轮播图位置
            const currentIndex = Array.from(img.parentElement.parentElement.children).indexOf(img.parentElement);
            imageSwiper.slideTo(currentIndex);
        });
    });

    // 关闭模态框
    document.querySelector('.close-button').addEventListener('click', function() {
        const modal = document.querySelector('.image-modal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

    // 处理模态框外的点击
    document.querySelector('.image-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});

// 初始化仪表盘
function initGauges() {
    // 户型稀缺度仪表盘
    new Chart(document.getElementById('sizeGauge'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [91, 9],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(200, 200, 200, 0.2)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            circumference: 180,
            rotation: -90,
            cutout: '80%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // 建筑质量仪表盘
    new Chart(document.getElementById('qualityGauge'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [95, 5],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(200, 200, 200, 0.2)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            circumference: 180,
            rotation: -90,
            cutout: '80%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // 采光条件仪表盘
    new Chart(document.getElementById('lightGauge'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [95, 5],
                backgroundColor: [
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(200, 200, 200, 0.2)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            circumference: 180,
            rotation: -90,
            cutout: '80%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // 居住舒适度仪表盘
    new Chart(document.getElementById('comfortGauge'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [90, 10],
                backgroundColor: [
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(200, 200, 200, 0.2)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            circumference: 180,
            rotation: -90,
            cutout: '80%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
} 



let pfps = document.querySelectorAll('.pfp');
let banners = document.querySelectorAll('.banner');
let logoBasket = document.querySelectorAll('.logo-basket');
let infoPlayerPoint = document.querySelectorAll('.infoPlayerPoint');
let profilePicture = document.querySelector(".profilePicture")
let redirectionBtn = document.querySelectorAll('.redirectionBtn');
logoBasket.forEach(logo => {
    logo.addEventListener('mouseover', function (){
        anime({
            targets : logoBasket,
            scale: 1.1,

        })
    })
    logo.addEventListener('mouseout', function(){
        anime({
            targets : logoBasket,
            scale: 1,
        })
    })
})

anime({
    targets : infoPlayerPoint,
    translateY : 65,
    delay : anime.stagger(100),
})

anime({
    targets : profilePicture,
    opacity: 1

})



anime({
    targets : redirectionBtn,
    translateX : -90,
    delay : anime.stagger(100),
})

// Animation du logo Basket

anime({
    targets : logoBasket,
    translateX : -150,
})




let animeShop = anime({
    targets : pfps,
    translateY : -50,

    delay : anime.stagger(100),
    opacity : 1,
    begin : function (){
        setTimeout(()=>{
            anime({
                targets : banners,
                delay: anime.stagger(100),
                translateX : -90,
                opacity : 1,
            })
        }, 100)
    }
})




let animeLogo = anime({
    targets : '.logo',
    translateX : 25,
    opacity : 1,
})
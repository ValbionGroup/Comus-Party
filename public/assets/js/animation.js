


let pfps = document.querySelectorAll('.pfp');
let banners = document.querySelectorAll('.banner');
let logoBasket = document.querySelectorAll('.logo-basket');

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


//
//
// anime({
//     targets : pfps,
//     translateY : -50,
//     delay : anime.stagger(100),
//
//     begin : function (){
//         setTimeout(()=>{
//             anime({
//                 targets : banners,
//                 delay: anime.stagger(100),
//                 translateX : -90
//             })
//         }, 100)
//     }
// })



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
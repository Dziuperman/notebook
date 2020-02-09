<template>
    <div id="main">
        <Header/>
        <div class="content">
            <router-view></router-view>
        </div>
        <vue-snotify></vue-snotify>
        <link rel="stylesheet"
              href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"
              integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
              crossorigin="anonymous">
    </div>
</template>

<script>
    import Header from './Header.vue';

    export default {
        name: 'main-app',
        components: {Header}
    }
</script>

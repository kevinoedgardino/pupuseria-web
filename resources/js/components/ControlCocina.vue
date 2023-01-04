<template>
    <main>
        <div class="parent">
            <div class="div1">
                <div v-if="pedidos.length" class="p-3">
                    <p><b>1# - {{ pedidos[0].cliente }}</b></p>
                    <div class="pedidos-cont">
                        <p><b>Pedidos:</b></p>
                        <ul class="high-panel d-flex flex-wrap flex-column">
                            <li v-for="orden in pedidos[0].pedidos">
                                {{ orden.producto }}: {{ orden.cantidad }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="div2">
                <div v-if="pedidos.length > 1" class="p-3">
                    <p><b>2# - {{ pedidos[1].cliente }}</b></p>
                    <div class="pedidos-cont">
                        <p><b>Pedidos:</b></p>
                        <ul class="high-panel d-flex flex-wrap flex-column">
                            <li v-for="orden in pedidos[1].pedidos">
                                {{ orden.producto }}: {{ orden.cantidad }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="div3">
                <div v-if="pedidos.length > 2" class="p-3">
                    <p><b>3# - {{ pedidos[2].cliente }}</b></p>
                    <div class="pedidos-cont">
                        <p><b>Pedidos:</b></p>
                        <ul class="low-panel d-flex flex-wrap flex-column">
                            <li v-for="orden in pedidos[2].pedidos">
                                {{ orden.producto }}: {{ orden.cantidad }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="div4">
                <div v-if="pedidos.length > 3" class="p-3">
                    <p><b>4# - {{ pedidos[3].cliente }}</b></p>
                    <div class="pedidos-cont">
                        <p><b>Pedidos:</b></p>
                        <ul class="low-panel d-flex flex-wrap flex-column">
                            <li v-for="orden in pedidos[3].pedidos">
                                {{ orden.producto }}: {{ orden.cantidad }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="div5">
                <div v-if="pedidos.length > 4" class="p-3">
                    <p><b>5# - {{ pedidos[4].cliente }}</b></p>
                    <div class="pedidos-cont">
                        <p><b>Pedidos:</b></p>
                        <ul class="low-panel d-flex flex-wrap flex-column">
                            <li v-for="orden in pedidos[4].pedidos">
                                {{ orden.producto }}: {{ orden.cantidad }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            pedidos: []
        }
    },
    methods: {
        async getPedidos() {
            try {
                const response = await axios.get('/pedidos/filter?state=P')
                if (typeof (response) === 'object') {
                    this.pedidos = response.data.reverse()
                }
            }
            catch (error) {

            }
        }
    },
    mounted() {
        setInterval(() => {
            this.getPedidos()
        },2000)
    }
}
</script>

<style scoped>
main {
    width: 100%;

    height: 90%;
    color: #000;
}

.high-panel {
    max-height: 250px;
}

.low-panel {
    max-height: 170px;
}

.parent {
    width: 100%;
    height: 100%;
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    grid-template-rows: repeat(5, 1fr);
    grid-column-gap: 3px;
    grid-row-gap: 3px;
}

.div1 {
    font-size: 1.3em;
    grid-area: 1 / 1 / 4 / 4;
    background: #fff;
}

.div2 {
    font-size: 1.3em;
    grid-area: 1 / 4 / 4 / 7;
    background: #fff;
}

.div3 {
    font-size: 1em;
    grid-area: 4 / 1 / 6 / 3;
    background: #fff;
}

.div4 {
    font-size: 1em;
    grid-area: 4 / 3 / 6 / 5;
    background: #fff;
}

.div5 {
    font-size: 1em;
    grid-area: 4 / 5 / 6 / 7;
    background: #fff;
}
</style>

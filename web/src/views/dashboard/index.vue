<template>
  <div class="app-container"/>
</template>

<script>
import { sysData } from '@/api/dashboard'

export default {
  name: 'Dashboard',
  data() {
    return {
      total: {
        orderSuccessNumber: 0,
        withdrawSuccessNumber: 0,
        pay: 0,
        service: 0,
        tranceNeed: 0
      },
      ipaynow: {
        fromAccount: '/',
        incomeAccount: '/'
      }
    }
  },
  created() {
  },
  mounted() {
    this.sysData()
  },
  methods: {
    sysData() {
      sysData().then(res => {
        this.total = res.data.data.total
        this.ipaynow = res.data.data.total
      }).catch(() => {})
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.data_card {
  margin: 20px;
}
.data_body {
  width: 600px;
  height: 200px;
  margin: 15px;
}
.data_number {
  font-size:50px;
  padding-top:20px;
}
</style>

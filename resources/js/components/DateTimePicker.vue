<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

export default {
  components: { DatePicker },
  data() {
    return {
      dataTimeRange: null,
    };
  },
  methods:{
  sendData: function(){
    this.$emit('sendDataTimeRange', this.dataTimeRange);
  }
}
};
</script>

<template>
  <date-picker
    @input="sendData"
    v-model="dataTimeRange"
    type="datetime"
    range
    placeholder="Select time when room is active"
  ></date-picker>
</template>

<style>
  .mx-input {
    font-size: 1rem;
    color: #6c757d;
    height: 2.4rem;
  }
  .mx-input:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }
</style>

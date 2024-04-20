<template>
  <ul>
    <li v-for="(weather, cityName) in weatherData" :key="cityName">
      <b>{{ cityName }}:</b>
      <span v-if="!weather.error">
        {{ weather.main.temp }}°C ({{ weather.weather[0].main }})
      </span>
      <span v-else>
        Error retrieving weather data.
      </span>
    </li>
  </ul>
    <div class="weather-app__wrapper w-[450px] max-w-lg text-white mb-15">
      <div class="weather-app__actual w-[450px] max-w-lg overflow-hidden bg-blue-900 mt-5 rounded-lg">
        <div class="weather-app__current flex justify-between items-center p-5">
          <div class="weather-app__deg flex items-center">
            <div class="weather-app__deg-celsius mr-4">
              <h3 class="text-[68px] font-bold">4<sup>o</sup>C</h3>
              <p class="weather-app__deg-feels">Feel like -1<sup>o</sup>C</p>
            </div>
            <div class="weather-app__deg-location">
              <h4 class="text-[18px] font-bold">Cloudy</h4>
              <p>Tokyo, Japan</p>
            </div>
          </div>
          <div class="weather-app__weather">
            Cloudy
          </div>
        </div>

        <!-- end current -->

        <coming-component></coming-component>
      </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      weatherData: {}
    };
  },
  mounted() {
    this.fetchWeatherData();
  },
  methods: {
    fetchWeatherData() {
      axios.get('/get-weather')
        .then(response => {
          this.weatherData = response.data;
        })
        .catch(error => {
          console.error('Error fetching weather data:', error);
        });
    }
  }
}
</script>

<template>
    <div class="weather-app__location-btn grid grid-cols-6 gap-2 w-full max-w-[950px] flex-wrap mx-auto mt-7">
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Tokyo</button>
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Yokohama</button>
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Kyoto</button>
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Osaka</button>
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Sapporo</button>
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Nagoya</button>
    </div>

    <div class="weather-app__wrapper w-full max-w-[450px] mx-auto text-white mb-15">
      <div>
        <form @submit.prevent="getWeather">
          <input type="hidden" v-model="cityName" class="js-city-name" placeholder="Enter city name">
        </form>

        <form @submit.prevent="getPlaces">
          <input type="hidden" v-model="cityName" class="js-city-name" placeholder="Enter city name">
        </form>
      </div>
      <div class="weather-app__actual w-[450px] max-w-lg overflow-hidden bg-[#00896d] mt-5 rounded-lg">
        <div v-for="(day, index) in weatherList" :key="day.dt">
          <div v-if="index === 0" class="weather-app__current flex justify-between items-center p-5">
            <div class="weather-app__deg flex items-center">
              <div class="weather-app__deg-celsius mr-4">
                <h3 class="text-[68px] font-bold">{{ Math.ceil(day.main.temp) }}°C</h3>
                <p class="weather-app__deg-feels">Feel like {{ Math.ceil(day.main.feels_like) }}°C</p>
              </div>
              <div class="weather-app__deg-location">
                <h4 class="text-[18px] font-bold">{{ day.weather[0].main }}</h4>
                <p>{{ weatherData.city.name }}, {{ weatherData.city.country }}</p>
              </div>
            </div>
            <div class="weather-app__weather">
              <img :src="getIconUrl(day.weather[0].icon, 2)" :alt="day.weather[0].description">
            </div>
          </div>

          <div class="weather-app__coming-weathers" v-if="index !== 0">
            <div class="weather-app__coming overflow-hidden p-5 text-sm bg-[#168e76] border-b border-gray-400">
              <div class="weather-app__days flex items-center">
                <div class="weather-app__day w-1/6">
                  <p>{{ getDayName(day.dt_txt) }}</p>
                  <p>{{ getMilitaryTime(day.dt_txt) }}</p>
                </div>
                <div class="weather-app__forecast flex items-center justify-center text-center w-2/3">
                  {{ day.weather[0].description }} <img :src="getIconUrl(day.weather[0].icon)" :alt="day.weather[0].description">
                </div>
                <div class="weather-app__deg w-1/6 flex flex-col justify-center items-center">
                  <p>{{ Math.ceil(day.main.temp) }}°C</p>
                  <p>{{ Math.ceil(day.main.feels_like) }}°C</p>
                </div>
              </div>
            </div>
          </div>
        </div>


      </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      weatherData: {},
      weatherList: {},
      cityName: ''
    };
  },
  mounted() {
    this.fetchWeatherData();
  },
  methods: {
    fetchWeatherData() {
      axios.get('/get-weather')
        .then(response => {
          this.weatherData = response.data.weather;
          this.weatherList = this.weatherData.list;
          console.log(response.data)
        })
        .catch(error => {
          console.error('Error fetching weather data:', error);
        });
    },
    getIconUrl(iconCode, size) {
      if (size > 1) {
        return `http://openweathermap.org/img/wn/${iconCode}@${size}x.png`;
      }

      return `http://openweathermap.org/img/wn/${iconCode}.png`;

    },
    getDayName(timestamp) {
      const date = new Date(timestamp);
      const day = date.getDay();
      const days = ['SUN', 'MON', 'TUE', 'WED', 'THUR', 'FRID', 'SAT'];
      return days[day];
    },
    getMilitaryTime(timestamp) {
      const date = new Date(timestamp);
      const hours = date.getHours().toString().padStart(2, '0');
      const minutes = date.getMinutes().toString().padStart(2, '0');
      return `${hours}:${minutes}`;
    },
    populateInput(event) {
      this.cityName = event.target.innerText;
      this.getWeather();
    },
    async getWeather() {
      try {
        const response = await axios.get('/get-weather', {
          params: {
            city: this.cityName, // Send the city name as a parameter
          },
        });
        this.weatherData = response.data.weather;
        this.weatherList = this.weatherData.list;
      } catch (error) {
        console.error(error);
      }
    }
  }
}
</script>

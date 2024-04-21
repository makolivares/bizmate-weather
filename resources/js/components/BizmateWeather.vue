<template>
  <div class="weather-app relative">
    <div v-if="loading" class="weather-app__loader flex items-center justify-center fixed w-full h-full z-10 top-0 left-0 bg-[#168e76] bg-opacity-70">
      <img src="/images/loader.gif" alt="loader" class="w-20">
    </div>
    <div class="weather-app__location-btn grid-cols-6 gap-2 w-full max-w-[950px] flex-wrap mx-auto mt-7 hidden md:grid">
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Tokyo</button>
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Yokohama</button>
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Kyoto</button>
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Osaka</button>
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Sapporo</button>
      <button class="bg-[#00896d] hover:bg-[#168e76] text-white font-bold py-2 px-4 rounded" @click="populateInput">Nagoya</button>
    </div>

    <div class="weather-app__location-select grid-cols-6 gap-2 px-3 w-full max-w-[950px] flex-wrap mx-auto mt-7 block md:hidden">
      <select id="countries" class="bg-[#00896d] text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:placeholder-gray-400 dark:text-white" @change="populateInputSelect">
        <option value="Tokyo">Tokyo</option>
        <option value="Yokohama">Yokohama</option>
        <option value="Kyoto">Kyoto</option>
        <option value="Sapporo">Sapporo</option>
        <option value="Nagoya">Nagoya</option>
      </select>
    </div>

    <div class="weather-places__container block md:flex justify-center">
      <div class="weather-app__wrapper w-full max-w-[1000px] px-3 md:px-0 md:max-w-[450px] mr-2 text-white mb-15">
        <div>
          <form @submit.prevent="getWeather">
            <input type="hidden" v-model="cityName" class="js-city-name">
          </form>

          <form @submit.prevent="getPlaces">
            <input type="hidden" v-model="cityName" class="js-city-name">
          </form>
        </div>
        <div class="weather-app__actual w-full md:w-[450px] max-w-[1000px] lg:max-w-lg overflow-hidden bg-[#00896d] mt-5 rounded-lg">
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
              <div class="weather-app__coming overflow-hidden p-5 text-sm bg-[#168e76] border-b border-[#186454]">
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

      <div class="weather-places__places w-full md:my-0 md:max-w-[450px] mt-5 px-3 md:px-0">
        <div class="weather-places__wrapper text-white p-5 overflow-hidden bg-[#00896d] mt-5 rounded-lg relative">
          <h2 class="text-center text-[24px] font-bold mb-4">Places near {{ cityName }}</h2>
          <div v-for="places in nearPlaces" :key="places.properties.name" class="weather__places-items border-b border-[#186454] p-4">
            <div class="weather__places-items-content">
              <h3 class="font-bold mb-2">Name: {{ places.properties.name }}</h3>
              <h3 class="font-bold mb-2" v-if="places.properties.name_international">International Name: {{ places.properties.name_international.en }}</h3>
              <p>Street: {{ places.properties.street }}</p>
              <p v-if="places.properties.city">City: {{ places.properties.city }}</p>
              <p v-if="places.properties.district">District: {{ places.properties.district }}</p>
              <p v-if="places.properties.contact">Phone: {{ places.properties.contact.phone }}</p>
              <p v-if="places.properties.website">Website: {{ places.properties.website }}</p>
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
      loading: true,
      weatherData: {},
      weatherList: {},
      nearPlaces: {},
      cityName: 'Tokyo'
    };
  },
  mounted() {
    this.fetchWeatherData();
    this.fetchPlacesData();
  },
  methods: {
    fetchPlacesData() {
      axios.get('/get-places')
        .then(response => {
          this.nearPlaces = response.data;
          this.loading = false;
        })
        .catch(error => {
          console.error('Error fetching weather data:', error);
        });
    },
    fetchWeatherData() {
      axios.get('/get-weather')
        .then(response => {
          this.weatherData = response.data;
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
      this.loading = true;
      this.getWeather();
      this.getPlaces();

    },
    populateInputSelect(event) {
      this.cityName = event.target.value;
      this.loading = true;
      this.getWeather();
      this.getPlaces();
    },
    async getWeather() {
      try {
        const response = await axios.get('/get-weather', {
          params: {
            city: this.cityName, // Send the city name as a parameter
          },
        });
        this.weatherData = response.data;
        this.weatherList = this.weatherData.list;
      } catch (error) {
        console.error(error);
      }
    },
    async getPlaces() {
      try {
        const response = await axios.get('/get-places', {
          params: {
            city: this.cityName, // Send the city name as a parameter
          },
        });
        this.nearPlaces = response.data;
        this.loading = false;
      } catch (error) {
        console.error(error);
      }
    }
  }
}
</script>

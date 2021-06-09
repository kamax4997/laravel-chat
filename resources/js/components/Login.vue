<template>
  <div class="login">
    <div class="login__inner-wrapper">
      <div :class="{ 'not-centered': loginWindow}"
           class="login__left">

        <div v-if="loginWindow > 0"
             role="button"
             @click="goBefore"
             class="login__back">
        </div>

        <!--  Welcome choice    -->
        <div v-if="loginWindow === 0"
             class="login__welcome-wrapper">
          <h5 class="login__welcome">Welcome to {{ settings.site_name }}</h5>

          <div class="login__buttons">
            <button type="button"
                    @click="resetLogInTypeData(2)"
                    class="btn--secondary">
              I'm New
            </button>

            <button type="button"
                    @click="resetLogInTypeData()"
                    class="btn--transparent">
              I already have an account
            </button>
          </div>

          <div class="login__activate-wrapper">
            <a href="activate-account" class="login__activate-account">
              Activate my account
            </a>
          </div>
        </div>

        <!-- Sign in as a guess -->
        <div :key=1 v-if="loginWindow === 2"
             class="login__guess">

          <div class="login__user-image">
            <loading v-if="loading"
                     :animation-duration="1000"
                     :size="80"
                     color="#ff1d5e"/>
            <img src="/storage/user-logo.png" class="login__user-logo">
            <h4>{{ signInAsGuest }}</h4>
          </div>

          <div class="login__name">
            <input type="text"
                   v-model="guessName"
                   class="input--text"
                   minlength="1"
                   maxlength="14"
                   name="guess"
                   placeholder="Guest"
                   @keydown="keyInputHandler"/>
          </div>

          <ul class="login__gender radio--buttons">
            <li class="login__gender-male">
              <input type="radio"
                     id="male"
                     value="m"
                     name="gender"
                     v-model="gender">
              <label for="male">
                Male
              </label>
              <div class="check">
                <div class="inside"></div>
              </div>
            </li>

            <li class="login__gender-female">
              <input type="radio"
                     id="female"
                     value="f"
                     name="gender"
                     v-model="gender">
              <label for="female">
                Female
              </label>
              <div class="check">
                <div class="inside"></div>
              </div>
            </li>
          </ul>

          <div class="login__sign-in">
            <button type="submit"
                    @click.prevent="login"
                    class="btn--secondary">
              Sign In
            </button>
          </div>

          <div class="login__footer">
            <a href=""
               class="login__create-account"
               @click.prevent="loginWindow = 3">
              Create an account
            </a>
          </div>
        </div>

        <!-- Sign in as a member -->
        <div v-if="loginWindow === 1"
             class="login__member">

          <div class="login__user-image">
            <loading v-if="loading"
                     :animation-duration="1000"
                     :size="80"
                     color="#ff1d5e"/>

            <img src="/storage/user-logo.png" class="login__user-logo">
            <h4> {{ signInAsMember }} </h4>
          </div>

          <b-row class="my-1">
            <b-col sm="12" class="login__email">
              <b-form-input type="email" class="input--text" v-model="email" :state="loginmailChk" placeholder="Email"></b-form-input>
            </b-col>
          </b-row>

          <b-row class="my-1">
            <b-col sm="12" class="login__password">
              <b-form-input type="password" class="input--text" v-model="password" :state="loginpassChk" placeholder="Password"></b-form-input>
            </b-col>
          </b-row>

          <div class="login__sign-in">
            <button type="submit"
                    @click.prevent="login"
                    class="btn--secondary">
              Sign In
            </button>
          </div>

          <div class="login__footer">
            <a href=""
               class="login__create-account">
              Reset my password
            </a>
          </div>
        </div>

        <div v-if="loginWindow === 3"
             class="login__member">

          <div class="login__user-image">
            <h4>Create account</h4>
          </div>

          <b-container fluid>
            <b-input-group prepend="First Name" class="login__name" :class='{"form__input-error": firstChk}'>
              <b-form-input type="text" 
                v-model="firstname" 
                placeholder="First Name">
              </b-form-input>
            </b-input-group>
            <b-input-group prepend="Last Name" class="login__name" :class='{"form__input-error": lastChk}'>
              <b-form-input type="text" 
                v-model="lastname"
                placeholder="Last Name">
              </b-form-input>
            </b-input-group>
            <b-input-group prepend="Birthday" class="login__name" :class='{"form__input-error": birthChk}'>
              <b-form-input
                v-model="birthday1"
                type="text"
                readonly
                class="datepicker-input"
                placeholder="dd/mm/yyyy"
                autocomplete="off"
              ></b-form-input>
              <b-input-group-append>
                <b-form-datepicker
                  right
                  button-only
                  v-model="birthday"
                  @context="oncontext"
                  aria-controls="example-input"
                ></b-form-datepicker>
              </b-input-group-append>
            </b-input-group>
            <b-input-group prepend="Gender" class="login__name">
              <b-form-select v-model="reggen" :options="gender_arr"></b-form-select>
            </b-input-group>
            <b-input-group prepend="Martial Status" class="login__name">
              <b-form-select v-model="martial" :options="martial_arr"></b-form-select>
            </b-input-group>
            <b-input-group prepend="Country" class="login__name">
              <b-form-select v-model="country" :options="country_arr" @change="countryChange"></b-form-select>
            </b-input-group>
            <b-input-group prepend="State" class="login__name" :class='{"form__input-error": stateChk}'>
              <b-form-select v-model="state" :options="state_arr" @change="stateChange"></b-form-select>
            </b-input-group>
            <b-input-group prepend="City" class="login__name" :class='{"form__input-error": cityChk}'>
              <b-form-select v-model="city" :options="city_arr"></b-form-select>
            </b-input-group>
          </b-container>

          <div class="login__sign-in">
            <button type="submit"
                    @click.prevent="nextStep(4)"
                    class="btn--secondary">
              Next Step
            </button>
          </div>
        </div>

        <div v-if="loginWindow === 4"
             class="login__member">

          <b-container fluid>
            <b-input-group prepend="Email Address" class="login__name" :class='{"form__input-error": mailChk}'>
              <b-form-input type="email" 
                v-model="regmail"
                @change="mailChg"
                placeholder="Email Address">
              </b-form-input>
            </b-input-group>
            <b-input-group prepend="User Name" class="login__name" :class='{"form__input-error": nameChk}'>
              <b-form-input type="text" 
                v-model="regname"
                @change="nameChg"
                placeholder="User Name">
              </b-form-input>
            </b-input-group>
            <b-input-group prepend="Password" class="login__name" :class='{"form__input-error": passChk}'>
              <b-form-input type="password" 
                v-model="regpass"
                placeholder="Password">
              </b-form-input>
            </b-input-group>
            <b-input-group prepend="Confirm Password" class="login__name" :class='{"form__input-error": passChk}'>
              <b-form-input type="password" 
                v-model="regconf"
                placeholder="Confirm Password">
              </b-form-input>
            </b-input-group>
            <b-input-group prepend="Secret Question" class="login__name">
              <b-form-select v-model="secret" :options="secret_arr"></b-form-select>
            </b-input-group>
            <b-input-group prepend="Secret Answer" class="login__name" :class='{"form__input-error": answChk}'>
              <b-form-input type="text" 
                v-model="regansw"
                placeholder="Secret Answer">
              </b-form-input>
            </b-input-group>
          </b-container>

          <div class="login__sign-in">
            <button type="submit"
                    @click.prevent="nextStep(5)"
                    class="btn--secondary">
              Next Step
            </button>
          </div>
        </div>

        <div v-if="loginWindow === 5"
             class="login__member">
          <div class="login__user-image">
            <loading v-if="loading"
                     :animation-duration="1000"
                     :size="80"
                     color="#ff1d5e"/>
            <img :src="imageData" class="login__user-logo">
          </div>

          <button type="button" class="btn--secondary upload-btn" @click.prevent="fileSelect">
            Upload Photo
          </button>

          <input
            class="file-input"
            ref="fileInput"
            type="file"
            @input="onSelectFile"
          >

          <div class="login__sign-in">
            <button type="submit"
                    @click.prevent="register"
                    class="btn--secondary complete-profile">
              Complete Profile
            </button>
          </div>
        </div>
      </div>

      <div :style="`background: url(${imagePath}) no-repeat`"
           class="login__right">
        <div class="login__right-inner-wrapper">
          <div class="login__brand-wrapper">
            <img src="/storage/logo.png" class="login__logo">
            <h1 class="login__app-name">{{ settings.site_name }}</h1>
            <div>{{ settings.site_slogan }}</div>
          </div>

          <div class="login__language-wrapper">
            <label for="language">Select Language:</label>
            <v-select name="language"
                      :reduce="language => language.code"
                      label="language"
                      :options="languages">
            </v-select>
          </div>
        </div>
      </div>
    </div>

    <!-- Show error dialog-->
    <l-dialog id="dialog-error"/>

  </div>
</template>

<script>
  import Settings from 'Mixins/Settings';
  import get from 'lodash/get';
  import LDialog from "./tools/LDialog";
  import { mapState, mapGetters, mapMutations } from "vuex";

  export default {
    components: {LDialog},
    mixins: [Settings],

    props: {
      /**
       * An array of advert images
       */
      images: {
        type: Array,
        default: () => []
      },
    },

    data() {
      return {
        firstChk: false,
        lastChk: false,
        birthChk: false,
        passChk: false,
        mailChk: false,
        nameChk: false,
        answChk: false,
        stateChk: false,
        cityChk: false,
        loginmailChk: null,
        loginpassChk: null,
        firstname: "",
        lastname: "",
        birthday: "",
        birthday1: "",
        reggen: "m",
        martial: '1',
        country: "",
        state: "",
        city: "",
        regmail: "",
        regname: "",
        regpass: "",
        regconf: "",
        secret: "1",
        regansw: "",
        imageData: "/storage/user-logo.png",
        loading: false,
        signInAsGuest: 'Sign in as guest',
        signInAsMember: 'Sign in as a member',
        imagePath: `storage/${this.images[0]}`,
        imageIndex: 0,
        accessToken: '',
        loginWindow: 0,
        isMember: false,
        language: 'en',
        gender_arr: [
          {value: 'm', text: 'Male'},
          {value: 'f', text: 'Female'}
        ],
        martial_arr: [
          {value: '1', text: 'Single'},
          {value: '2', text: 'In A Relationship'},
          {value: '3', text: 'Engaged'},
          {value: '4', text: 'Married'},
          {value: '5', text: 'In a Open Relationship'},
          {value: '6', text: 'Separated'},
          {value: '7', text: 'Divorced'},
          {value: '8', text: 'Widowed'}
        ],
        secret_arr: [
          {value: '1', text: 'What are the last five digits of your driver\'s license number?'},
          {value: '2', text: 'What is your grandmother\'s(on your mother\'s side) maiden name?'},
          {value: '3', text: 'What is the name of the hospital you were born?'},
          {value: '4', text: 'What was your first car?'},
          {value: '5', text: 'What was your first job?'}
        ],
        languages: [
          {code: 'en', language: 'Engish'},
          {code: 'fr', language: 'French'},
          {code: 'ge', language: 'German'},
        ],
        country_arr: [],
        state_arr: [],
        city_arr: [],
        guessName: '',
        gender: 'm',
        email: '',
        password: '',
      }
    },

    computed: {
      ...mapState('chat', [
        'errorMessages'
      ]),
      ...mapGetters('chat', [
        'hasError'
      ]),
    },

    mounted() {
      this.initCountry();
      setInterval(() => {
        this.imagePath = 'storage/' + this.images[this.imageIndex];

        this.imageIndex = this.imageIndex + 1;
        if (this.imageIndex > this.images.length -1) {
          this.imageIndex = 0;
        }

      }, 10000);
    },

    methods: {
      /**
       * Validate input.
       */
      validateGuessName() {
        const regex = /[^A-Z0-9\S]/gi;
        const matches = regex.exec(this.guessName);

        return matches;
      },
      initCountry() {
        this.$axios.get(`/api/auth/country`, {
        })
        .then((response) => {
          const resp = response.data;
          if(resp.country && resp.country.length > 0) {
            let arrList = [];
            for(let ii = 0; ii < resp.country.length; ii++) {
              const selItem = resp.country[ii];
              arrList.push({value: selItem.co_code, text: selItem.co_name});
            }

            this.country_arr = arrList;
            this.country = arrList[0].value;

            arrList = [];
            for(let ii = 0; ii < resp.region.length; ii++) {
              const selItem = resp.region[ii];
              arrList.push({value: selItem.rg_code, text: selItem.rg_name});
            }

            this.state_arr = arrList;
            // this.state = arrList[0].value;

            arrList = [];
            for(let ii = 0; ii < resp.city.length; ii++) {
              const selItem = resp.city[ii];
              arrList.push({value: selItem.ci_id, text: selItem.ci_name});
            }

            this.city_arr = arrList;
            // this.city = arrList[0].value;
          }
        })
        .catch((error) => {
          const message = get(error, 'response.data.message', 'An error has occured!');
          this.$store.commit('chat/setErrorMessages', [message]);
        });
      },
      countryChange() {
        console.log('here');
        this.$axios.post(`/api/auth/region`, {
          country: this.country
        })
        .then((response) => {
          const resp = response.data;
          if(resp && resp.region.length > 0) {
            let arrList = [];
            for(let ii = 0; ii < resp.region.length; ii++) {
              const selItem = resp.region[ii];
              arrList.push({value: selItem.rg_code, text: selItem.rg_name});
            }

            this.state_arr = arrList;
            this.state = arrList[0].value;

            if(resp.city.length > 0) {
              arrList = [];
              for(let ii = 0; ii < resp.city.length; ii++) {
                const selItem = resp.city[ii];
                arrList.push({value: selItem.ci_id, text: selItem.ci_name});
              }

              this.city_arr = arrList;
              this.city = arrList[0].value;
            } else {
              this.city_arr = [];
              this.city = "";
            }
          }
        })
        .catch((error) => {
          const message = get(error, 'response.data.message', 'An error has occured!');
          this.$store.commit('chat/setErrorMessages', [message]);
        });
      },
      stateChange() {
        this.$axios.post(`/api/auth/city`, {
          country: this.country,
          region: this.state
        })
        .then((response) => {
          const resp = response.data.city;
          if(resp && resp.length > 0) {
            let arrList = [];
            for(let ii = 0; ii < resp.length; ii++) {
              const selItem = resp[ii];
              arrList.push({value: selItem.ci_id, text: selItem.ci_name});
            }

            this.city_arr = arrList;
            this.city = arrList[0].value;
          }
        })
        .catch((error) => {
          const message = get(error, 'response.data.message', 'An error has occured!');
          this.$store.commit('chat/setErrorMessages', [message]);
        });
      },
      fileSelect() {
        this.$refs.fileInput.click();
      },
      onSelectFile () {
        const files = this.$refs.fileInput.files
        if (files && files[0]) {
          const reader = new FileReader
          reader.onload = e => {
            this.imageData = e.target.result
          }
          reader.readAsDataURL(files[0])
        }
      },
      nameChg() {
        console.log('namechg');
        this.nameChk = false;
        if(this.regname.length < 2 || this.regname.length > 14) {
          this.nameChk = true;
        }
      },
      mailChg() {
        this.mailChk = false;
        console.log('mailChg');
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(this.regmail.length === 0 || !re.test(this.regmail)) {
          this.mailChk = true;
        }
      },
      nextStep(ind) {
        let formValid = true;
        if(ind == 4) {
          this.firstChk = false;
          this.lastChk = false;
          this.birthChk = false;
          this.stateChk = false;
          this.cityChk = false;
          if(this.firstname.length < 2 || this.firstname.length > 20) {
            this.firstChk = true;
            formValid = false;
          }

          if(this.lastname.length < 2 || this.lastname.length > 20) {
            this.lastChk = true;
            formValid = false;
          }

          if(this.birthday.length === 0) {
            this.birthChk = true;
            formValid = false;
          }

          if(this.city.length === 0) {
            this.cityChk = true;
            formValid = false;
          }

          if(this.state.length === 0) {
            this.stateChk = true;
            formValid = false;
          }
        } else if(ind == 5) {
          this.mailChk = false;
          this.nameChk = false;
          this.passChk = false;
          this.answChk = false;
          const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if(this.regmail.length === 0 || !re.test(this.regmail)) {
            this.mailChk = true;
            formValid = false;
          }

          if(this.regname.length < 2 || this.regname.length > 14) {
            this.nameChk = true;
            formValid = false;
          }

          if(this.regpass.length < 5 || this.regpass !== this.regconf) {
            this.passChk = true;
            formValid = false;
          }

          if(this.regansw.length < 5) {
            this.answChk = true;
            formValid = false;
          }
        }

        if(formValid) {
          this.loginWindow = ind
        }
      },
      oncontext() {
        const dateArr = this.birthday.split('-');
        if(dateArr.length === 3) {
          this.birthday1 = dateArr[2] + "/" + dateArr[1] + "/" + dateArr[0];
        }
      },
      goBefore() {
        this.loginWindow = this.loginWindow > 2 ? this.loginWindow - 1 : 0;
      },
      register() {
        let formData = new FormData();
        
        const files = this.$refs.fileInput.files
        if (files && files[0]) {
          formData.append('file', files[0]);
        }

        formData.append('name', this.regname)
        formData.append('email', this.regmail)
        formData.append('password', this.regpass)
        formData.append('password_confirmation', this.regconf)
        formData.append('firstname', this.firstname)
        formData.append('lastname', this.lastname)
        formData.append('birthday', this.birthday)
        formData.append('gender', this.reggen)
        formData.append('martial', this.martial)
        formData.append('country', this.country)
        formData.append('region', this.state)
        formData.append('city', this.city)
        formData.append('question', this.secret)
        formData.append('secret', this.regansw)

        this.loading = true;
        this.$axios.post(`/api/auth/signup`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then((response) => {
          this.loading = false;
          this.loginWindow = 2;
        })
        .catch((error) => {
          const message = get(error, 'response.data.errors');
          let errMsg = [];
          for(let key in message) {
            if(message.hasOwnProperty(key)) {
              errMsg.push(message[key][0])
            }
          }

          this.$store.commit('chat/setErrorMessages', errMsg);
          this.loading = false;
        });
      },
      /**
       * login
       */
      login() {
        // We check if the entered guess name is valid.
        if(this.validateGuessName()) {
          this.$store.commit('chat/setErrorMessages', [`Invalid characters detected, please only enter letters and numbers. No space allowed`]);
          return;
        }

        let formValid = true;
        this.loginmailChk = null;
        this.loginpassChk = null;
        if(this.email.length === 0) {
          this.loginmailChk = false;
          formValid = false;
        }

        if(this.password.length === 0) {
          this.loginpassChk = false;
          formValid = false;
        }

        if(formValid) {
          this.loading = true;
          this.$axios.post(`/api/auth/login`, {
            email: this.email,
            password: this.password,
            gender: this.gender,
            guess: this.guessName,
            remember_me: true,
          })
          .then((response) => {
            this.accessToken = get(response, 'data', []);

            if(this.loginWindow === 1) {
              this.signInAsGuest = `Welcome Guest_${this.$options.filters.capitalize(this.accessToken.user_name)}`;
            } else {
              this.signInAsMember = `Welcome ${this.$options.filters.capitalize(this.accessToken.user_name)}`;
            }

            setTimeout(() => {
              window.location.href = `/?token=${this.accessToken.access_token}&csrf=${this.accessToken.csrf}`;
            }, 3000);
          })
          .catch((error) => {
            const message = get(error, 'response.data.message', 'An error has occured!');
            this.$store.commit('chat/setErrorMessages', [message]);

            this.loading = false;
          });
        }
      },
      /**
       * Reset the param being passed depending on the login type.
       *
       * @param loginType
       */
      resetLogInTypeData(loginType = 1) {
        this.loginWindow = loginType;

        // Guess
        if (loginType === 1) {
          this.email = '';
          this.password = '';

          return;
        }

        this.guessName = '';
      },
      /**
       * Restrict special character input.
       *
       * @param event
       */
      keyInputHandler(event) {
        const regex = /[^A-Z0-9\S]/gi;
        const matches = regex.exec(event.key);

        if (matches) {
          event.preventDefault();
        }
      }
    },

    watch: {
      // Displays the error dialog.
      hasError(value) {
        if(value) {
          this.$bvModal.show('dialog-error');
        }
      }
    }
  }
</script>
<style lang="scss">
  /**
   * Tab style
   */
  .el-tabs {
    &__header {
      margin: 0;
    }
  }
</style>

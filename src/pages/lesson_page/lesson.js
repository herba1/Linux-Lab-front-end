import VanillaTerminal from "./termC/term.js";
import { NavigationLesson } from "../../components/NavigationLesson/index.js";

const sectionLesson = document.querySelector(".section--lesson");

class LessonManager {
  lesson = 0;
  currentSection = 'basics';
  currentSectionSize = 0;
  user = 0;
  lessons = [];
  constructor() {
    this.lesson = 0;
    this.currentSection = 'basics'
    // may change this later to identify user
    this.user = 0;
    this.fetchUserInfoInit();
    this.fetchLessons();

  }

  setupListeners(){
    document.addEventListener('section:update',this.handleLessonSectionChange)
  }

  async fetchUserInfoInit(){
    try{
      const request = await fetch("../../testAPI/userInfo.json");
      if(!request.ok){
        throw new Error('Could not load user info');
      }
      const data = await request.json();
      this.lesson = data[this.user]['lesson'];
      this.currentSection =  data[this.user]['section'];
    }

    catch(error){
      console.error(error);
    }
  }

  async fetchUserInfo(){
    try{
      const request = await fetch("../../testAPI/userInfo.json");
      if(!request.ok){
        throw new Error('Could not load user info');
      }
      const data = await request.json()
      this.lesson = data[this.user]['lesson'];
      this.currentSection =  data[this.user]['section'];
      this.broadcastUpdate();
    }
    catch(error){
      console.error(error);
    }
  }

  // this should only run once at the beginning
  async fetchLessons(){
    try{
      const request = await fetch("../../testAPI/lessons.json")
      if(!request.ok){throw new Error(`could not get lessons ${request.status}`)}
      const data = await request.json();
      this.lessons = data;
      console.log(this.lessons);
      this.currentSectionsectionSize = this.lessons[this.currentSection][0][`section__size`];
    }
    catch(error){
      console.error(error);
    }
  }

  broadcastUpdate(){
    const event = new CustomEvent(`state:update`,{
      detail:{
        user:{
          currentLessonId: this.lesson,
          currentSection: this.currentSection,
        },
        lessons:this.lessons,
      }
    })
    console.log(detail);
    document.dispatchEvent(event);
  }

  // handle lesson and section changes bundled
  handleLessonSectionChange = async (e)=>{
    const { section, lessonId, action} = e.detail;
    if(action = 'next'){
      // if we are at last lesson or beyond lesson scope
      if(lessonId >= this.sectionSize){
        lessonId = this.sectionSize;
        console.log(`cant go over section size`)
      }
    }
  
    try {
      const response = await fetch("../../testAPI/updateUserInfo.php", {
        method: "POST",
        headers: {
          "Content-type": "application/json",
        },
        body: JSON.stringify({
          section:section,
          lesson: lessonId,
        }),
      });
      if (!response.ok) {
        throw new Error(`response error`, response.status);
      }
      this.fetchUserInfo();
    } catch (error) {
      console.log(`error saving ${error}`);
    }
  }

  

  eventNewInfo(data){
    const eventNewInfo = new CustomEvent("userinfo:update", { detail: {
      
    }});
    dispatchEvent(eventNewInfo);
  }
}

class lessonDisplay {
  curSection = "basics";
  // LESSONS START FROM 1
  curLesson = 1;
  sectionSize = 0;
  modules = {};

  constructor(container, lessons = null) {
    this.container = document.querySelector(container);
    this.nextButton = document.querySelector(".lesson__button--next");
    this.prevButton = document.querySelector(".lesson__button--prev");
    this.progBar = document.querySelector(".progress-bar__meter");
    this.misc = document.querySelector(".lesson");
    this.statusCross = document.querySelector(".lesson__status--cross");
    this.statusCheck = document.querySelector(".lesson__status--check");

    // bind the async function to use our locals;
    this.postInfo = this.postInfo.bind(this);

    this.getInfo();
    this.initListeners();
    this.getLessons();
  }
  initialize() {
    this.changeSection();
    this.updateMeter();
    this.updateStatus();
    this.render();
  }

  initListeners() {
    this.nextButton.addEventListener("click", this.nextLesson);
    this.prevButton.addEventListener("click", this.prevLesson);
    this.misc.addEventListener("click", this.toggleLessonComplete);
    // custom command success event
    document.addEventListener("command-success", this.handleCorrectEvent);
  }
  update() {
    this.render();
    this.updateMeter();
    this.updateStatus();
  }
  render() {
    this.container.replaceChildren();
    this.container.innerHTML = `<h1 class="lesson__title">${
      this.modules[this.curSection][this.curLesson]["title"]
    }</h1>
        <div class="lesson__content">${
          this.modules[this.curSection][this.curLesson][`content`]
        }</div>`;
  }
  nextLesson = () => {
    if (this.curLesson >= this.sectionSize) return;
    ++this.curLesson;
    // update user cur lesson
    this.postInfo();
    this.update();
  };
  prevLesson = () => {
    if (this.curLesson <= 1) return;
    --this.curLesson;
    // update user cur lesson
    this.postInfo();
    this.update();
  };
  changeSection() {
    //    this.sectionSize = section[this.curSection][0]['section__size'];
    // the 0 part of the section is the meta data [0]
    this.sectionSize = this.modules[this.curSection][0]["section__size"];
    console.log(this.sectionSize);
  }
  updateMeter() {
    let completedCount = this.modules[this.curSection].reduce((sum, lesson) => {
      return sum + (lesson.completed ? 1 : 0);
    }, 0);
    let progValue = (completedCount / this.sectionSize) * 100;
    this.progBar.value = progValue;
    if (progValue === 100) {
      // change section stustua
    }
  }
  updateStatus() {
    if (this.modules[this.curSection][this.curLesson][`completed`] === true) {
      this.statusCheck.classList.remove(`hidden`);
      this.statusCross.classList.add(`hidden`);
    } else {
      this.statusCheck.classList.add(`hidden`);
      this.statusCross.classList.remove(`hidden`);
    }
  }
  toggleLessonComplete = () => {
    this.modules[this.curSection][this.curLesson][`completed`] =
      !this.modules[this.curSection][this.curLesson][`completed`];
    this.updateMeter();
    this.updateStatus();
  };

  // this will need to be done in DB or something to keep track of user info
  async getInfo() {
    try {
      const request = await fetch("../../testAPI/userInfo.json");
      if (!request.ok) {
        throw new Error("Could not load user info");
      }
      const data = await request.json();
      // at 0 simply refers to first user in our case
      this.curLesson = data[0].lesson;
      this.curSection = data[0].section;
      // this.getLessons();
    } catch (error) {
      console.log(`error ${error}`);
    }
  }

  //fetch the lesson after user info
  async getLessons() {
    try {
      const request = await fetch("../../testAPI/lessons.json");
      if (!request.ok) {
        throw new Error("Could not load lesson");
      }
      const data = await request.json();
      this.modules = data;
      console.log(this.modules);
      // call init because we will update everything
      this.initialize();
    } catch (error) {
      console.log(`error ${error}`);
    }
  }
  // we should send back this lesson and this
  async postInfo() {
    try {
      const response = await fetch("../../testAPI/updateUserInfo.php", {
        method: "POST",
        headers: {
          "Content-type": "application/json",
        },
        // we want to update the user info current lesson and section
        // for demonstration basics is hardcoded
        // we are seinding this json string
        body: JSON.stringify({
          section: this.curSection,
          lesson: this.curLesson,
        }),
      });
      if (!response.ok) {
        throw new Error(`response error`, response.status);
      }
      const data = await response.json();
    } catch (error) {
      console.log(`error saving ${error}`);
    }
  }
  handleCorrectEvent = (e) => {
    this.showSuccessMessage();
    // get lessons will update everything
    this.getLessons();
  };

  showSuccessMessage() {
    const successMessage = `
            <div class="overlay--success">
                <img src="../assets/SVGs/Tux.svg.png" alt="tux" class="logo">
                <h3 class="overlay__message">well done!</h3> 
            </div>
            `;
    const overlayContainer = document.createElement("div");
    overlayContainer.classList.add("overlay__container");
    overlayContainer.classList.add("overlay-popup");
    overlayContainer.innerHTML = successMessage;
    sectionLesson.prepend(overlayContainer);
    setTimeout(() => {
      overlayContainer.classList.remove("overlay-popout");
      overlayContainer.classList.add("overlay-fadeout");
    }, 1000 * 2);
    setTimeout(() => {
      overlayContainer.remove();
    }, 1000 * 3);
  }
}

const lessonDisplayController = new lessonDisplay(".lesson");
const terminal = new VanillaTerminal({
  apiEndpoint: "../../../api_commands.php",
});
const sidebarLesson = new NavigationLesson(
  ".sidebar__container",
  ".sidebar__button--open"
);

terminal.mount("#terminal__container");


const lessonManager = new LessonManager();
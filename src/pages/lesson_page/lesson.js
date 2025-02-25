import VanillaTerminal from "./termC/term.js";

const sidebarBtn = document.querySelector('.top__sidebar');
const sidebarBtnClose = document.querySelector('.sidebar__button')
const sidebar = document.querySelector('.sidebar');

const showDropdownButton = document.querySelector('.dropdown__button--open');
const dropdown = document.querySelector('.top__dropdown')
showDropdownButton.addEventListener('click',toggleDropdown)

function toggleDropdown(e){
    dropdown.classList.toggle('hidden');
}

function showDropdown(e){
    dropdown.classList.remove('hidden');
}
function hideDropdown(e){
    dropdown.classList.add('hidden');
}


sidebarBtn.addEventListener('click',()=>{
    sidebar.classList.toggle('hidden');
})
sidebarBtnClose.addEventListener('click',()=>{
    sidebar.classList.toggle('hidden');
})
// this is no longer in use and is reference for structure of the json
const section = {
    basics:[
        {section__size:1, section__completed:false,},
        {
            id: 1,
            section: 'basics',
            title: 'cat',
            completed: false,
            content: `<p>what should we do today or i dont even know im just tping</p>
                    <p>this is suppposed to be some blahballhlsldajflalf salfjsaflasf</p>
                    <p>blahballhlsldajflalf salfjsafljflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf asfjflalf jflalf jflalf jflalf </p>
                    <code>ls directory</code>
                    <p>blahballhlsldajflalf sjflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf jflalf asfjflalf jflalf jflalf jflalf </p>
                    `,
        },
    ],
}

class lessonManager{
    constructor(){

    }
}


class lessonDisplay{
    curSection = 'basics';
    // LESSONS START FROM 1
    curLesson = 1;
    sectionSize = 0;
    modules = {};
    
    constructor(container,lessons = null){
        this.container = document.querySelector(container);
        this.nextButton = document.querySelector('.lesson__button--next');
        this.prevButton= document.querySelector('.lesson__button--prev');
        this.progBar = document.querySelector('.progress-bar__meter');
        this.misc = document.querySelector('.lesson');
        this.statusCross = document.querySelector('.lesson__status--cross');
        this.statusCheck = document.querySelector('.lesson__status--check');

        this.getInfo();

        // this.initListeners();
        // this.changeSection();
        // this.updateMeter();
    }
    initialize(){
        this.initListeners();
        this.changeSection();
        this.updateMeter();
        this.render();

    }


    initListeners(){
        this.nextButton.addEventListener('click',this.nextLesson);
        this.prevButton.addEventListener('click',this.prevLesson);
        this.misc.addEventListener('click',this.toggleLessonComplete);
    }
    update(){
        this.render();
        this.updateMeter();
        this.updateStatus();
    }
    render(){
        this.container.replaceChildren();
        this.container.innerHTML = `<h1 class="lesson__title">${this.modules[this.curSection][this.curLesson]['title']}</h1>
        <div class="lesson__content">${this.modules[this.curSection][this.curLesson][`content`]}</div>`;
    }
    nextLesson= ()=>{
        if(this.curLesson >= this.sectionSize) return;
        ++this.curLesson;
        this.update();
    }
    prevLesson= ()=>{
        if(this.curLesson <= 1) return;
        --this.curLesson;
        this.update();
    }
    changeSection(){
    //    this.sectionSize = section[this.curSection][0]['section__size'];
       this.sectionSize = this.modules[this.curSection][0]['section__size'];
       console.log(this.sectionSize);
    }
    updateMeter(){
       let completedCount = this.modules[this.curSection].reduce((sum,lesson)=>{
        return (sum + (lesson.completed ? 1 : 0));
       },0)
       let progValue = (completedCount / this.sectionSize)*100;
       this.progBar.value=progValue;
       if(progValue===100){
        // change section stustua
       }
    }
    updateStatus(){
        if(this.modules[this.curSection][this.curLesson][`completed`] === true){
            this.statusCheck.classList.remove(`hidden`);
            this.statusCross.classList.add(`hidden`);
        }
        else{
            this.statusCheck.classList.add(`hidden`);
            this.statusCross.classList.remove(`hidden`);
        }
    }
    toggleLessonComplete=()=>{
        this.modules[this.curSection][this.curLesson][`completed`] = !this.modules[this.curSection][this.curLesson][`completed`];
        this.updateMeter();
        this.updateStatus();
    }

    // this will need to be done in DB or something to keep track of user info
    async getInfo(){
        try{
            const request = await fetch('../../testAPI/userInfo.json');
            if(!request.ok){
                throw new Error('Could not load user info');
            }
            const data = await request.json();
            // at 0 simply refers to a certain user
            this.curLesson = data[0].lesson;
            this.curSection= data[0].section;
            this.getLessons();
        }        
        catch(error){
            console.log(`error ${error}`);
        }
    }
    
    //fetch the lesson after user info
    async getLessons(){
        try{
            const request = await fetch('../../testAPI/lessons.json');
            if(!request.ok){
                throw new Error('Could not load lesson');
            }
            const data = await request.json();
            this.modules = data;
            console.log(this.modules)
            this.initialize();
            // return data[this.curSection];
        }        
        catch(error){
            console.log(`error ${error}`);
        }
    }
    // we should send back this lesson and this
    async postInfo(){

    }

    async postLessons(){

    }
};

const lessonDisplayController = new lessonDisplay('.lesson');
// lessonDisplayController.render();

const terminal = new VanillaTerminal({
    apiEndpoint:'../../../api_commands.php',
});
terminal.mount('#terminal__container');
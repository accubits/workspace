import { Component, OnInit, HostListener, ViewChild, ElementRef } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Router, ActivatedRoute } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { Configs } from '../../config';
import { TaskDataService } from '../../shared/services/task-data.service';
import { ToastService } from '../../shared/services/toast.service';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { TaskSandbox } from '../task.sandbox';
import { SortablejsModule } from 'angular-sortablejs/dist';
import { CKEditorModule } from 'ngx-ckeditor';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-create-taskpop',
  templateUrl: './create-taskpop.component.html',
  styleUrls: ['./create-taskpop.component.scss']
})

export class CreateTaskpopComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  public todayDate: any = new Date();
  showResppopup: boolean = false;
  showParentSection: boolean = false;
  showParticipants: boolean = false;
  showPartList: boolean = false;
  checkingItem: string = '';
  searchTextParent: '';
  searchTextApprvr: '';
  searchText: '';
  searchParticipants: '';
  showRepeatTaskSection: boolean = false;
  showResPrsnList: boolean = false;
  attachmentSection: boolean = false;
  checklistSection: boolean = false;
  common_popup: boolean = false;
  activeRpTab: string = 'all';
  activeParentTab: string = 'allTaskparent';
  activeApproverTab: string = 'allApprover';
  activeParticiapntTab: string = 'all';
  showMoreDates: boolean = false;
  isValidated: boolean = true;
  temp_popup: boolean = false;
  selectedTemplateName: string = '';
  showParentTaskList: boolean = false;
  showApproverlist: boolean = false;
  loggedUserSlug = '';
  loggedUserName = '';

  /* Preparing Owl Date[Start] */
  date: Date = new Date();
  settings = {
    bigBanner: true,
    timePicker: true,
    format: 'dd-MM-yyyy',
    defaultOpen: false,
    hour12Timer: true
  };
  /* Preparing Owl Date[End] */

  @ViewChild('inputFile') myInputVariable: ElementRef;

  language = 'en';
  editorValue = '';
  editorConfig = {
    removeButtons: '',
  };

  constructor(
    public taskDataService: TaskDataService,
    public taskSandbox: TaskSandbox,
    public router: Router,
    public actStreamDataService: ActStreamDataService,
    public spinner: Ng4LoadingSpinnerService,
    public toastService: ToastService,
    private cookieService: CookieService
  ) { }

  ngOnInit() {
     this.taskDataService.createTask.approver = {
      approverName: this.cookieService.get('userName'),
      approverSlug: this.cookieService.get('userSlug')
    }
    this.taskDataService.createTask.responsiblePerson = {
      responsiblePersonId: this.cookieService.get('userSlug'),
      responsiblePersonName: this.cookieService.get('userName')
    }
    // ck editor
    this.editorConfig.removeButtons = 'Save,body,Source,Language,NewPage,Font,Image,DocProps,Preview,Print,Templates,document,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,RemoveFormat,Outdent,Indent,Blockquote,CreateDiv,JustifyBlock,BidiLtr,BidiRtl,Link,Unlink,Anchor,CreatePlaceholder,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,InsertPre,Styles,Format,FontSize,TextColor,BGColor,UIColor,Maximize,ShowBlocks,button1,button2,button3,oembed,MediaEmbed,About';
    // Initial executions
    this.taskSandbox.getReposiblePerson();  // Getting resposible persons List
    this.taskSandbox.getParentTaks();  // Getting Parent task list
    this.taskSandbox.getTaskTemplates();  // Getting task templates
    //this.taskDataService.createTask.title = this.actStreamDataService.createTask.title;
    // console.log(this.router.url);
    //alert("fsdfsf");
    //  this.taskDataService.createTask.repeat = null;


    // Initially removing selected responsible person form partcipant list
    // let selUserinList = this.taskDataService.responsiblePersons.list.filter(
    //   user => user.slug === this.taskDataService.createTask.responsiblePerson.responsiblePersonId)[0];
    // let idx = this.taskDataService.responsiblePersons.list.indexOf(selUserinList)
    // this.taskDataService.responsiblePersons.list[idx]['existing'] = true
  }

  ngOnDestroy() {
    this.taskDataService.resetCreateTask();
  }

  /*######################### AREA TO MANAGE SELECTING RESPOSIBLE PERSON & PARTICIPANTS[START] ############### */

  /* Handling search and listing of res person[start] */
  initOrChangeResPrsnList(): void {
    // this.searchText ? this.activeRpTab = 'search' : this.activeRpTab = 'all';
    this.taskDataService.responsiblePersons.searchText = this.searchText;
    this.taskSandbox.getReposiblePerson();
  }

  resetRepPerson(): void {
    setTimeout(() => {
      if (this.searchText) {
        this.searchText = '';
        this.taskDataService.responsiblePersons.searchText = this.searchText;
        this.taskSandbox.getReposiblePerson();
      }
    }, 100)



  }
  /* Handling search and listing of res person[end] */

  /* Handling search and listing of participant person[start] */
  initOrChangeparticipantList(): void {
    this.searchParticipants ? this.activeParticiapntTab = 'search' : this.activeParticiapntTab = 'all';
    this.taskDataService.responsiblePersons.searchText = this.searchParticipants;
    this.taskSandbox.getReposiblePerson(); // Usig the same API for getting responsible person
  }

  resetPartcipnts(): void {
    this.searchParticipants = '';
    this.taskDataService.responsiblePersons.searchText = this.searchParticipants;
    this.taskSandbox.getReposiblePerson();

  }
  /* Handling search and listing of participant[end] */

  /* selecting a resposible Prsn[Start] */
  selctRespPerson(respPrsn): void {
    this.taskDataService.createTask.responsiblePerson = {
      responsiblePersonId: respPrsn.slug,
      responsiblePersonName: respPrsn.employee_name
    }
  }
  /* selecting a resposible Prsn[End] */

  /* Remove a resposible Prsn[Start] */
  removeRespPrsn(): void {
    this.taskDataService.createTask.responsiblePerson = {
      responsiblePersonId: '',
      responsiblePersonName: ''
    };
  }
  /* Remove a resposible Prsn[End] */

  /* Selecting participant[Start] */
  selectPartcipants(participant): void {
    // Checking if the participant already selected
    let existingParticpants = this.taskDataService.createTask.assignees.filter(
      part => part.assigneeSlug === participant.slug)[0];

    if (existingParticpants) {
      // toast to handle already added participant
      return;
    }

    this.taskDataService.createTask.assignees.push({
      assigneeSlug: participant.slug,
      assigneeName: participant.employee_name
    });
    participant.existing = true;
    this.taskDataService.createTask.participantIds.push(participant.slug);
    this.taskDataService.createTask.to_all_participants = false;
  }
  /* Selecting participant[End] */

  /* select all employees[Start] */
  selectAllEmployees(): void {
    this.taskDataService.createTask.assignees = [];
    this.taskDataService.createTask.participantIds = [];
    this.taskDataService.createTask.to_all_participants = true;
    this.showPartList = false;
    this.taskSandbox.getReposiblePerson();
  }
  /* select all employees[end] */

  /* remove all employees[Start] */
  removeAllEmployees(): void {
    this.taskDataService.createTask.to_all_participants = false;
  }
  /* remove all employees[end] */


  /* Remove participant[Start] */
  removePartcipants(participant, index): void {

    // inserting selected user back to user list
    let selUserinList = this.taskDataService.responsiblePersons.list.filter(
      user => user.slug === participant.assigneeSlug)[0];
    let idx = this.taskDataService.responsiblePersons.list.indexOf(selUserinList)
    this.taskDataService.responsiblePersons.list[idx].existing = false

    // Removing participant from participant list
    this.taskDataService.createTask.assignees.splice(index, 1);
    this.taskDataService.createTask.participantIds.splice(this.taskDataService.createTask.participantIds.indexOf(participant.slug));
  }
  /* Remove participant[End] */

  /* ######################### AREA TO MANAGE SELECTING RESPOSIBLE PERSON & PARTICIPANTS[END] ############### */

  /* Handling serach and listing of parent task[start] */
  initOrChangeParentTask(): void {
    //   this.searchTextParent ? this.activeParentTab = 'search' : this.activeRpTab = 'all';
    this.taskDataService.parentTasks.searchText = this.searchTextParent;
    this.taskSandbox.getParentTaks();
  }
  /* Handling serach and listing of parent@ViewChild('inputFile') myInputVariable: ElementRef;

  /* selecting a resposible Prsn[Start] */
  removeParentTask(selParentTask: any): void {
    this.taskDataService.createTask.parentTask = {
      parentTaskSlug: '',
      parentTaskTitle: ''
    }
  }
  /* selecting a resposible Prsn[End] */

  /* removing a parent task[Start] */
  selectParentTask(selParentTask: any): void {
    this.taskDataService.createTask.parentTask = {
      parentTaskSlug: selParentTask.slug,
      parentTaskTitle: selParentTask.title,

    }
  }
  /* removing a parent task[End] */

  /* selecting a approval Prsn[Start] */
  selectApprover(apprvr: any): void {
    this.taskDataService.createTask.approver = {
      approverName: apprvr.employee_name,
      approverSlug: apprvr.slug
    }
  }
  /* selecting a approval Prsn[End] */

  /* selecting a approval Prsn[Start] */
  removeApprover(): void {
    this.taskDataService.createTask.approver = {
      approverName: '',
      approverSlug: ''
    }
  }
  /* selecting a approval Prsn[End] */

  /* Handling search and listing of approver[start] */
  initOrChangeApprvrList(): void {
    //this.searchTextApprvr ? this.activeApproverTab = 'searchApprover' : this.activeApproverTab = 'allApprover';
    this.taskDataService.responsiblePersons.searchText = this.searchTextApprvr;
    this.taskSandbox.getReposiblePerson();
  }
  /* Handling search and listing of approver[end] */

  /* File upload[Start] */
  uploadTaskFiles(taskFiles): void {
    console.log(taskFiles)
    if (taskFiles.length == 0) return;
    for (let i = 0; i < taskFiles.length; i++) {
      let existingFile = this.taskDataService.createTask.fileList.filter(
        file => file.name === taskFiles[i].name)[0];

      let inExistingFile = this.taskDataService.createTask.existingFiles.filter(
        file => file.name === taskFiles[i].name)[0];
      if (!existingFile && !inExistingFile) {
        this.taskDataService.createTask.fileList.push(taskFiles[i]);
      }
    }
    this.myInputVariable.nativeElement.value = '';
  }
  /* File upload[End] */

  /* Remove Uploaded File [Start] */
  removeUploadedFiles(index): void {
    this.taskDataService.createTask.fileList.splice(index, 1);
  }
  /* Remove Uploaded File [End] */

  /* Remove Existing Files [Start] */
  removeExistingFiles(index): void {
    this.taskDataService.createTask.existingFiles.splice(index, 1);
  }
  /* Remove Existing Files [Start] */

  /* Add  & Remove checklist[Start] */
  addCheckList(): void {
    if (this.checkingItem) {
      this.taskDataService.createTask.checklists.push({
        description: this.checkingItem,
        checklistStatus: false
      });
      this.checkingItem = '';
    }
  }

  removeAcheckListItem(index): void {
    this.taskDataService.createTask.checklists.splice(index, 1);
  }
  /* Add  & Remove checklist[End] */

  /* Selecting Frequency for task repeating[Start]  */
  selctFrequency(frequncyType: string): void {
    this.taskDataService.createTask.repeat.repeatType = frequncyType
  }
  /* Selecting Frequency for task repeating[End]  */

  /* Validating $ Creating New Task[Start] */
  validateNewTask(): boolean {
    this.isValidated = true;
    // Validating title and description
    if (!this.taskDataService.createTask.title) {
      this.isValidated = false;
      this.toastService.Error('', 'Title missing')

    }
    if (!this.taskDataService.createTask.description) {
      this.isValidated = false;
      this.toastService.Error('', 'Description missing')

    }

    // Validating due date and responsible person
    if (!this.taskDataService.createTask.endDate) {
      this.isValidated = false;
      this.toastService.Error('', 'Due date missing')
    }

    if (!this.taskDataService.createTask.responsiblePerson.responsiblePersonId) {
      this.isValidated = false;
      this.toastService.Error('', 'Responsible person missing')
    }

    if (!this.taskDataService.createTask.approveTaskCompleted && !this.taskDataService.createTask.approver.approverSlug) {
      this.isValidated = false;
      this.toastService.Error('', 'Approver missing')
    }

    if (this.showParentSection === true && this.taskDataService.createTask.parentTask.parentTaskSlug === '') {
      this.isValidated = false;
      this.toastService.Error('', 'Parent task missing')
    }

    return this.isValidated;
  }



  createNewTask(): void {
    if (this.taskDataService.apicall.inprogress) return;
    if (!this.validateNewTask()) return;
    this.taskDataService.apicall.inprogress = true;
    this.spinner.show();
    this.taskSandbox.createNewTask();
  }
  /* Validating $ Creating New Task[End] */

  closeCreatePopup() {
    // console.log(this.router.url);
    // if (this.router.url === '/authorized/task/task-create') {
    //   this.router.navigate(['authorized/activity/as_recent']);
    // }
    this.taskDataService.showCreatetaskpopup.show = false;
  }

  /* Load from template[Start] */
  loadFromTemplate(selectedTemplate): void {
    this.taskDataService.resetCreateTaskTepmlate();
    this.taskDataService.taskTemplates.selectedTemplateSlug = selectedTemplate.slug;
    this.selectedTemplateName = selectedTemplate.title;
    this.taskSandbox.loadFromTemplate();
  }
  /* Load from template[End] */

  repeatTaskChange() {
    if (this.taskDataService.createTaskRepeat.showRepeat) {
      this.taskDataService.createTask.repeat = {
        repeatType: 'week',
        repeatEvery: 1,
        week: {
          Sunday: false,
          Monday: false,
          Tuesday: false,
          Wednesday: false,
          Thursday: false,
          Friday: false,
          Saturday: false
        },
        ends: {
          never: false,
          on: null,
          after: ""
        }
      }
    }


  }

  approveTaskCompleted(approveTaskCompleted) {
     if (approveTaskCompleted === true) {
      this.taskDataService.createTask.approver.approverSlug = null;
    }
  }
}
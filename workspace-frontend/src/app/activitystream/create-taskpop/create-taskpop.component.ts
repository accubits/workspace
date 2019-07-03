import { Component, OnInit, HostListener, Inject } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Router, ActivatedRoute } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../activity.sandbox';
import { SortablejsModule } from 'angular-sortablejs/dist';
import { CKEditorModule } from 'ngx-ckeditor';

@Component({
  selector: 'app-create-taskpop',
  templateUrl: './create-taskpop.component.html',
  styleUrls: ['./create-taskpop.component.scss']
})

export class CreateTaskpopComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  showParentSection: boolean = false;
  showPartList: boolean = false;
  checkingItem: string = '';
  searchTextParent: '';
  searchTextApprvr: '';
  searchText: '';
  searchParticipants: '';
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

  language = 'en';
  editorValue = '';
  editorConfig = {
    removeButtons: '',
  };

  constructor(
    public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService,
    public router: Router,
    public spinner: Ng4LoadingSpinnerService
  ) { }

  ngOnInit() {
    this.editorConfig.removeButtons = 'Save,body,Source,Language,NewPage,Font,Image,DocProps,Preview,Print,Templates,document,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,RemoveFormat,Outdent,Indent,Blockquote,CreateDiv,JustifyBlock,BidiLtr,BidiRtl,Link,Unlink,Anchor,CreatePlaceholder,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,InsertPre,Styles,Format,FontSize,TextColor,BGColor,UIColor,Maximize,ShowBlocks,button1,button2,button3,oembed,MediaEmbed,About';
    this.editorConfig.removeButtons = 'Save,body,Source,Language,NewPage,DocProps,Preview,Print,Templates,document,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,RemoveFormat,Outdent,Indent,Blockquote,CreateDiv,JustifyBlock,BidiLtr,BidiRtl,Link,Unlink,Anchor,CreatePlaceholder,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,InsertPre,Styles,Format,FontSize,TextColor,BGColor,UIColor,Maximize,ShowBlocks,button1,button2,button3,oembed,MediaEmbed,About,Image';
    this.activitySandboxService.getTaskTemplates();  // Getting task templates
    this.activitySandboxService.getParentTaks();  // Getting Parent task list
  }

  ngOnDestroy() {
  }

  /* Handling search and listing of res person[start] */
  initOrChangeResPrsnList(): void {
    this.activitySandboxService.getReposiblePerson();
  }
  /* Handling search and listing of res person[end] */

  /* Handling reset task participant[start] */
  resetTaskParticipant() {
    this.searchParticipants = '';
    this.activitySandboxService.getReposiblePerson();
  }
  closeOverlay(){
    this.common_popup = false;
  }
 /* Handling reset task participant[end] */

  /* Handling search and listing of participant person[start] */
  initOrChangeparticipantList(): void {
    this.searchParticipants ? this.activeParticiapntTab = 'search' : this.activeParticiapntTab = 'all';
    this.activitySandboxService.getReposiblePerson();
  }
  /* Handling search and listing of participant[end] */

  /* selecting a resposible Prsn[Start] */
  selctRespPerson(respPrsn): void {
    this.actStreamDataService.createTask.responsiblePerson = {
      responsiblePersonId: respPrsn.slug,
      responsiblePersonName: respPrsn.employee_name
    }
  }
  /* selecting a resposible Prsn[End] */

  /* Remove a resposible Prsn[Start] */
  removeRespPrsn(): void {
    this.actStreamDataService.createTask.responsiblePerson = {
      responsiblePersonId: '',
      responsiblePersonName: ''
    };
  }
  /* Remove a resposible Prsn[End] */

  /* Selecting participant[Start] */
  selectPartcipants(participant): void {
    let existingParticpants = this.actStreamDataService.createTask.assignees.filter(
      part => part.assigneeSlug === participant.slug)[0];
    if (existingParticpants) {
      return;
    }
    this.actStreamDataService.createTask.assignees.push({
      assigneeSlug: participant.slug,
      assigneeName: participant.employee_name
    });
    participant.existing = true;
    this.actStreamDataService.createTask.participantIds.push(participant.slug);
    this.actStreamDataService.toUsers.toAllEmployee = false;
  }
  /* Selecting participant[End] */

  /* select all employees[Start] */
  selectAllEmployees(): void {
    this.actStreamDataService.toUsers.toAllEmployee = true;
    this.actStreamDataService.toUsers.toUsers = [];
    this.actStreamDataService.createTask.assignees = [];
    this.showPartList = false;
    this.activitySandboxService.getReposiblePerson();
  }
  /* select all employees[end] */

  /* remove all employees[Start] */
  removeAllEmployees(): void {
    this.actStreamDataService.toUsers.toAllEmployee = false;
    this.actStreamDataService.toUsers.toUsers = [];
  }
  /* remove all employees[end] */

  /* Remove participant[Start] */
  removePartcipants(participant, index): void {
    let selUserinList = this.actStreamDataService.responsiblePersons.list.filter(
      user => user.slug === participant.assigneeSlug)[0];
    let idx = this.actStreamDataService.responsiblePersons.list.indexOf(selUserinList)
    this.actStreamDataService.responsiblePersons.list[idx].existing = false
     this.actStreamDataService.createTask.assignees.splice(index, 1);
    this.actStreamDataService.createTask.participantIds.splice(this.actStreamDataService.createTask.participantIds.indexOf(participant.slug));
  }
  /* Remove participant[End] */

 /* Handling serach and listing of parent task[start] */
  initOrChangeParentTask(): void {
    this.searchTextParent ? this.activeParentTab = 'search' : this.activeRpTab = 'all';
    this.actStreamDataService.parentTasks.searchText = this.searchTextParent;
    this.activitySandboxService.getParentTaks();
  }
  /* Handling serach and listing of parent task[end] */

  /* selecting a resposible Prsn[Start] */
  removeParentTask(selParentTask: any): void {
    this.actStreamDataService.createTask.parentTask = {
      parentTaskSlug: '',
      parentTaskTitle: ''
    }
  }
  /* selecting a resposible Prsn[End] */

  /* removing a parent task[Start] */
  selectParentTask(selParentTask: any): void {
    this.actStreamDataService.createTask.parentTask = {
      parentTaskSlug: selParentTask.slug,
      parentTaskTitle: selParentTask.title,

    }
  }
  /* removing a parent task[End] */

  /* selecting a approval Prsn[Start] */
  selectApprover(apprvr: any): void {
    this.actStreamDataService.createTask.approver = {
      approverName: apprvr.employee_name,
      approverSlug: apprvr.slug
    }
  }
  /* selecting a approval Prsn[End] */

  /* selecting a approval Prsn[Start] */
  removeApprover(): void {
    this.actStreamDataService.createTask.approver = {
      approverName: '',
      approverSlug: ''
    }
  }
  /* selecting a approval Prsn[End] */

  /* Handling search and listing of approver[start] */
  initOrChangeApprvrList(): void {
    this.activitySandboxService.getReposiblePerson();
  }
  /* Handling search and listing of approver[end] */

  /* File upload[Start] */
  uploadTaskFiles(taskFiles): void {
    console.log(taskFiles)
    if (taskFiles.length == 0) return;
    for (let i = 0; i < taskFiles.length; i++) {
      this.actStreamDataService.createTask.fileList.push(taskFiles[i]);

    }
  }
  /* File upload[End] */

  /* Remove Uploaded File [Start] */
  removeUploadedFiles(index): void {
    this.actStreamDataService.createTask.fileList.splice(index, 1);
  }
  /* Remove Uploaded File [End] */

  /* Add  & Remove checklist[Start] */
  addCheckList(event): void {
    if (event.key === "Enter" && this.checkingItem) {
      this.actStreamDataService.createTask.checklists.push({
        description: this.checkingItem,
        checklistStatus: false
      });
      this.checkingItem = '';
    }
  }

  removeAcheckListItem(index): void {
    this.actStreamDataService.createTask.checklists.splice(index, 1);
  }
  /* Add  & Remove checklist[End] */

  /* Selecting Frequency for task repeating[Start]  */
  selctFrequency(frequncyType: string): void {
    this.actStreamDataService.createTask.repeat.repeat_type = frequncyType
  }
  /* Selecting Frequency for task repeating[End]  */

  /* Validating $ Creating New Task[Start] */
  validateNewTask(): boolean {
    this.isValidated = true;
    if (!this.actStreamDataService.createTask.title) this.isValidated = false;
    if (!this.actStreamDataService.createTask.description) this.isValidated = false;
    if (!this.actStreamDataService.createTask.approver.approverSlug && this.actStreamDataService.createTask.approveTaskCompleted === false) this.isValidated = false;
    return this.isValidated;
  }
  createNewTask(): void {
    if (!this.validateNewTask()) return;
    this.spinner.show();
    if (this.actStreamDataService.createTask.showRepeatTaskSection === false) {
      this.actStreamDataService.createTask.repeat = null;
    }
    this.activitySandboxService.createNewTask();
  }
 /* Validating $ Creating New Task[end] */ 

  /* update task [Start] */
  updateTask(): void {
    if (!this.validateNewTask()) return;
    this.activitySandboxService.updateTask();
  }
  /* update task [end] */

/* cancel task [Start] */
  cancelTask() {
    this.isValidated = true;
    this.activitySandboxService.resetData();
  }
/* cancel task [end] */

  /* Load from template[Start] */
  loadFromTemplate(selectedTemplate): void {
    this.actStreamDataService.taskTemplates.selectedTemplateSlug = selectedTemplate.slug;
    this.selectedTemplateName = selectedTemplate.title;
    this.activitySandboxService.loadFromTemplate();
  }
  /* Load from template[End] */

  approveTaskCompleted(approveTaskCompleted) {
    if (approveTaskCompleted === true) {
     this.actStreamDataService.createTask.approver.approverSlug = null;
   }
 }
}
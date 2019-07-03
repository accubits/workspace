import { Component, OnInit, HostListener, Inject } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { FormsModule } from '@angular/forms';
import { Configs } from '../../config';
import { TaskDataService } from '../../shared/services/task-data.service';
import { TaskSandbox } from '../task.sandbox';
import { SortablejsModule } from 'angular-sortablejs/dist';
import { CKEditorModule } from 'ngx-ckeditor';
import { ToastService } from '../../shared/services/toast.service';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-task-editpop',
  templateUrl: './task-editpop.component.html',
  styleUrls: ['./task-editpop.component.scss']
})
export class TaskEditpopComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
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
  showParentTaskList: boolean = false;
  attachmentSection: boolean = false;
  checklistSection: boolean = false;
  common_popup: boolean = false;
  showApproverlist: boolean = false;
  activeRpTab: string = 'all';
  activeParentTab: string = 'allTaskparent';
  activeApproverTab: string = 'allApprover';
  activeParticiapntTab: string = 'all';
  showMoreDates: boolean = false;
  isValidated: boolean = true;
  temp_popup: boolean = false;
  // ck Editor

  language = 'en';
  editorValue = '';
  editorConfig = {
    removeButtons: ''
  };

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


  constructor(
    public taskDataService: TaskDataService,
    private taskSandbox: TaskSandbox,
    private spinner: Ng4LoadingSpinnerService,
    public toastService: ToastService,
    private router: Router,
    private route: ActivatedRoute,
  ) { }


  ngOnInit() {
    this.editorConfig.removeButtons = 'Save,body,Source,Language,NewPage,Font,Image,DocProps,Preview,Print,Templates,document,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,RemoveFormat,Outdent,Indent,Blockquote,CreateDiv,JustifyBlock,BidiLtr,BidiRtl,Link,Unlink,Anchor,CreatePlaceholder,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,InsertPre,Styles,Format,FontSize,TextColor,BGColor,UIColor,Maximize,ShowBlocks,button1,button2,button3,oembed,MediaEmbed,About';
    this.taskDataService.editTask.showRepeat = false;
    this.taskSandbox.getReposiblePerson();
    this.taskSandbox.getParentTaks();

    for (let i = 0; i < this.taskDataService.editTaskTemplate.assignees.length; i++) {
      this.taskDataService.editTaskTemplate.participantIds.push(this.taskDataService.editTaskTemplate.assignees[i].assigneeSlug);
    }
  }

  ngOnDestroy() {
    this.taskDataService.resetEdit();
  }


  //   /*######################### AREA TO MANAGE SELECTING RESPOSIBLE PERSON & PARTICIPANTS[START] ############### */

  /* Handling search and listing of res person[start] */
  initOrChangeResPrsnList(): void {
    this.searchText ? this.activeRpTab = 'search' : this.activeRpTab = 'all';
    this.taskDataService.responsiblePersons.searchText = this.searchText;
    this.taskSandbox.getReposiblePerson();
  }

  resetRepPerson(): void {
    this.searchText = '';
    this.taskDataService.responsiblePersons.searchText = this.searchText;
    this.taskSandbox.getReposiblePerson();

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
    this.taskDataService.editTaskTemplate.responsiblePerson = {
      responsiblePersonName: respPrsn.employee_name,
      responsiblePersonId: respPrsn.slug,
    }

  }
  /* selecting a resposible Prsn[End] */

  /* select all employees[Start] */
  selectAllEmployees(): void {
    this.taskDataService.editTaskTemplate.assignees = [];
    this.taskDataService.editTaskTemplate.participantIds = [];
    this.taskDataService.taskDetails.selectedTaskDetails.isAllParticipants = true;
    this.showPartList = false;
    this.taskSandbox.getReposiblePerson();
  }
  /* select all employees[end] */

  /* Remove a resposible Prsn[Start] */
  removeRespPrsn(): void {
    this.taskDataService.editTaskTemplate.responsiblePerson = {
      responsiblePersonName: '',
      responsiblePersonId: '',
    }
  }
  /* Remove a resposible Prsn[End] */

  /* remove all employees[Start] */
  removeAllEmployees(): void {
    this.taskDataService.taskDetails.selectedTaskDetails.isAllParticipants = false;
  }
  /* remove all employees[end] */

  /* Selecting participant[Start] */
  selectPartcipants(participant): void {
    let existingParticpants = this.taskDataService.editTaskTemplate.assignees.filter(
      part => part.slug === participant.slug)[0];

    if (existingParticpants) {
      return;
    }

    this.taskDataService.editTaskTemplate.assignees.push(
      {
        "assigneeName": participant.employee_name,
        "assigneeSlug": participant.employee_slug
      }
    );

    participant.existing = true;
    this.taskDataService.editTaskTemplate.participantIds.push(participant.slug);
    this.taskDataService.taskDetails.selectedTaskDetails.isAllParticipants = false;
  }
  /* Selecting participant[End] */

  /* Remove participant[Start] */
  removePartcipants(participant, index): void {
    // inserting selected user back to user list
    let selUserinList = this.taskDataService.responsiblePersons.list.filter(
      user => user.slug === participant.assigneeSlug)[0];
    let idx = this.taskDataService.responsiblePersons.list.indexOf(selUserinList)
    this.taskDataService.responsiblePersons.list[idx].existing = false

    // Removing participant from participant list
    this.taskDataService.editTaskTemplate.assignees.splice(index, 1);
    this.taskDataService.editTaskTemplate.participantIds.splice(this.taskDataService.editTaskTemplate.participantIds.indexOf(participant.slug));
  }  // this.searchText = '';
  // this.searchTextParent = '';
  /* Remove participant[End] */

  //   /* ######################### AREA TO MANAGE SELECTING RESPOSIBLE PERSON & PARTICIPANTS[END] ############### */



  /* Handling serach and listing of parent task[start] */
  initOrChangeParentTask(): void {
    this.searchTextParent ? this.activeParentTab = 'search' : this.activeRpTab = 'all';
    this.taskDataService.parentTasks.searchText = this.searchTextParent;
    this.taskSandbox.getParentTaks();
  }
  /* Handling serach and listing of parent task[end] */

  /* selecting a Parent Task[Start] */
  selectParentTask(selParentTask: any): void {
    this.taskDataService.editTaskTemplate.parentTask = {
      parentTaskSlug: selParentTask.slug,
      parentTaskTitle: selParentTask.title
    }
  }
  /* selecting a Parent Task[End] */

  /* remove a Parent Task[Start] */
  removeParentTask(selParentTask: any): void {
    this.taskDataService.editTaskTemplate.parentTask = {
      parentTaskSlug: '',
      parentTaskTitle: ''
    }
  }
  /* remove a Parent Task[End] */

  /* selecting a approval Prsn[Start] */
  selectApprover(apprvr: any): void {
    this.taskDataService.editTaskTemplate.approver = {
      approverName: apprvr.employee_name,
      approverSlug: apprvr.slug
    }
    // this.searchText = '';
  }
  /* selecting a approval Prsn[End] */

  /* removing a approval Prsn[Start] */
  removeApprover(): void {
    this.taskDataService.editTaskTemplate.approver = {
      approverName: '',
      approverSlug: ''
    }
    // this.searchText = '';
  }
  /* removing a approval Prsn[End] */

  /* Handling search and listing of approver[start] */
  initOrChangeApprvrList(): void {
    this.searchTextApprvr ? this.activeApproverTab = 'searchApprover' : this.activeApproverTab = 'allApprover';
    this.taskDataService.responsiblePersons.searchText = this.searchTextApprvr;
    this.taskSandbox.getReposiblePerson();
  }
  /* Handling search and listing of approver[end] */


  /* File upload[Start] */
  uploadTaskFiles(taskFiles): void {
    if (taskFiles.length == 0) return;
    for (let i = 0; i < taskFiles.length; i++) {
      this.taskDataService.editTaskTemplate.newFileList.push(taskFiles[i]);

    }

    //Add your condition for allowing only specific file


    //this.taskDataService.createTask.fileList = taskFiles;
  }
  /* File upload[End] */

  /* Remove Newly Uploaded File [Start] */
  removeNewlyUploadedFiles(index): void {
    this.taskDataService.editTaskTemplate.newFileList.splice(index, 1);
  }
  /* Remove Newly Uploaded File [End] */

  /* Remove Existing Files [Start] */
  removeExistingFiles(index): void {
    this.taskDataService.editTaskTemplate.existingFiles.splice(index, 1);
  }
  /* Remove Existing Files [Start] */

  /* Add  & Remove checklist[Start] */
  addCheckList(): void {
    if (this.checkingItem) {
      this.taskDataService.editTaskTemplate.checklists.push({
        slug: "",
        description: this.checkingItem,
        checklistStatus: false
      });
      this.checkingItem = '';
    }
  }
  

  removeAcheckListItem(index): void {
    this.taskDataService.editTaskTemplate.checklists.splice(index, 1);
  }
  /* Add  & Remove checklist[End] */

  /* Selecting Frequency for task repeating[Start]  */
  selctFrequency(frequncyType: string): void {
    this.taskDataService.editTaskTemplate.repeat.repeatType = frequncyType;
  }
  /* Selecting Frequency for task repeating[End]  */

  /* Validating $ Creating New Task[Start] */
  validateEditedTask(): boolean {
    this.isValidated = true;

    if (!this.taskDataService.editTaskTemplate.title) {
      this.isValidated = false;
      this.toastService.Error('', 'Title missing')

    }
    if (!this.taskDataService.editTaskTemplate.description) {
      this.isValidated = false;
      this.toastService.Error('', 'Description missing')
    }

    // Validating due date and responsible person
    if (!this.taskDataService.editTaskTemplate.endDate) {
      this.isValidated = false;
      this.toastService.Error('', 'Due date missing')
    }

    if (!this.taskDataService.editTaskTemplate.responsiblePerson.responsiblePersonId) {
      this.isValidated = false;
      this.toastService.Error('', 'Responsible person missing')
    }

    if (!this.taskDataService.editTaskTemplate.approver.approverSlug) {
      this.isValidated = false;
      this.toastService.Error('', 'Approver missing')
    }

    return this.isValidated;
  }

  /*Edit task[Start] */
  editTask(): void {
    if (!this.validateEditedTask()) return;
    this.taskDataService.apicall.inprogress = true;
    this.taskSandbox.editTask();
    console.log("dasdad");
    this.taskSandbox.getTaskList();
  }
  closeEdit(): void{
    this.taskDataService.resetEdit();
    this.taskDataService.taskDetails.showEditTaskPop = false
  }
  /*Edit task[End] */

  // cancelEdit(){
  //   this.taskDataService.taskDetails.showEditTaskPop = false;
  //   // this.taskDataService.taskDetails.selectedTask = this.taskDataService.getTasks.overviewTaskList[idx].slug;
  //   this.router.navigate([{outlets:{detailpopup:['task-detail',this.taskDataService.taskDetails.selectedTask]}}], {
  //     relativeTo: this.route.parent // <--- PARENT activated route.
  // }); 
  // }
 
}

import { Component, OnInit } from '@angular/core';
import { CalendarDataService } from '../../shared/services/calendar-data.service';
import { CKEditorModule } from 'ngx-ckeditor';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../../activitystream/activity.sandbox';
import { UtilityService } from '../../shared/services/utility.service';

@Component({
  selector: 'app-create-new',
  templateUrl: './create-new.component.html',
  styleUrls: ['./create-new.component.scss']
})
export class CreateNewComponent implements OnInit {
  selectPop : boolean = false;
  activeRpTab: string = 'all';
  showResPrsnList: boolean = false;
  clickforMore : boolean = false;
  clicktoHide : boolean = true;
  availableList : boolean = false;
  repeatList : boolean = false;
  impList : boolean = false;
  calendarList : boolean = false;
  language = 'en';
  editorValue = '';
  editorConfig = {
    removeButtons: '',
  };
  userlistingdrop = false;
  reminderOpt = false;
  reminderDisabled = true;
  isValidated = true;
  type = '';
  count = '';
  typeDd = false;
  AvaDd = false;
  repDd = false;
  impDd = false;
  startTime: any;
  endTime: any;

  constructor(
    public actStreamDataService: ActStreamDataService,
    public calendarDataService : CalendarDataService,
    public activitySandboxService: ActivitySandboxService,
    private utilityService: UtilityService,
  ) { }

  ngOnInit() {
    this.editorConfig.removeButtons = 'Save,body,Source,Language,NewPage,Font,Image,DocProps,Preview,Print,Templates,document,Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,RemoveFormat,Outdent,Indent,Blockquote,CreateDiv,JustifyBlock,BidiLtr,BidiRtl,Link,Unlink,Anchor,CreatePlaceholder,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,InsertPre,Styles,Format,FontSize,TextColor,BGColor,UIColor,Maximize,ShowBlocks,button1,button2,button3,oembed,MediaEmbed,About';
  }

  closepop(){
    //alert("ffsd");
    this.calendarDataService.createPopup.showPopup = false;
  }
  showSelect(){
    this.selectPop =! this.selectPop;
  }
  showPersonList(){
    this.showResPrsnList =! this.showResPrsnList;
  }
  closePersonList(){
    this.showResPrsnList = false;
  }
  toggleDown(){
    this.clickforMore =! this.clickforMore;
    this.clicktoHide = false;
  }
  toggleUp(){
    this.clickforMore =! this.clickforMore;
    this.clicktoHide = true;
  }
  availableShow(){
    this.availableList =! this.availableList;
  }
  repeatShow(){
    this.repeatList =! this.repeatList;
  }
  calendarShow(){
    this.calendarList =! this.calendarList;
  }
  impShow(){
    this.impList =! this.impList;
  }
  
   /* Get users list [Start] */
   initOrChangeparticipantsList(): void {
    this.activitySandboxService.getReposiblePerson();
  }
  /* Get users list [End]*/

  /* reset user list[Start] */
  resetUserList(): void {
    this.actStreamDataService.responsiblePersons.searchParticipantsTxt = '';
  }
  /*  reset user list[end] */

   /* Remove user from toUser list [Start] */
   removeUsers(user): void {
    let existingUsers = this.actStreamDataService.toUsers.toUsers.filter(
      part => part.userSlug === user.userSlug)[0];
    if (existingUsers) {
      let idx = this.actStreamDataService.toUsers.toUsers.indexOf(existingUsers);
      if (idx !== -1) this.actStreamDataService.toUsers.toUsers.splice(idx, 1);
    }
    let addUser = this.actStreamDataService.responsiblePersons.list.filter(
      part => part.slug === user.userSlug)[0];
    let idx = this.actStreamDataService.responsiblePersons.list.indexOf(addUser);
    this.actStreamDataService.responsiblePersons.list[idx].existing = false
  }
  /* Remove user from toUser list[end] */

  /* remove all employees[Start] */
  removeAllEmployees(): void {
    this.actStreamDataService.toUsers.toAllEmployee = false;
    this.actStreamDataService.toUsers.toUsers = [];
  }
  /* remove all employees[end] */

  /* select all employees[Start] */
  selectAllEmployees(): void {
    this.actStreamDataService.toUsers.toAllEmployee = true;
    this.actStreamDataService.toUsers.toUsers = [];
    this.userlistingdrop = false;
    this.activitySandboxService.getReposiblePerson();
  }
  /* select all employees[end] */

  /* Select users list [Start] */
  selectUser(users): void {
    let existingUsers = this.actStreamDataService.toUsers.toUsers.filter(
      part => part.userSlug === users.slug)[0];
    if (existingUsers) {
      // toast to handle already added participant
      return;
    }
    this.actStreamDataService.toUsers.toUsers.push({
      userSlug: users.slug,
      name: users.employee_name
    });
    users.existing = true;
    this.actStreamDataService.toUsers.toAllEmployee = false;
  }
  /* Select users list [End] */

    /* set reminder for event[start] */
    setReminder() {
      if (this.reminderOpt) {
        this.reminderDisabled = !this.reminderDisabled;
      }
      else {
        this.type = '';
        this.count = '';
        this.reminderDisabled = true;
      }
    }
    /* set reminder for event[end] */
  
    /* give type for event[start] */
    setType(type) {
      this.type = type;
      this.typeDd = false;
    }
    /* give type for event[end] */
  
    /* set repeate option for event[start] */
    setRepeateOpt(RepeateOpt) {
      this.actStreamDataService.createEvent.eventRepeat = RepeateOpt;
      this.repDd = false;
    }
    /* set repeate option for event[end] */
  
    /* set availability option for event[start] */
    setAvaOpt(AvaOpt) {
      this.actStreamDataService.createEvent.eventAvailability = AvaOpt;
      this.AvaDd = false;
    }
    /* set availability option for event[end] */
  
    /* set important option for event[start] */
    setImpOpt(ImpOpt) {
      this.actStreamDataService.createEvent.eventImportance = ImpOpt;
      this.impDd = false;
    }
    /* set important option for event[end] */
  
    /* close more options from event[start] */
    clearMoreOption() {
      this.actStreamDataService.createEvent.eventRepeat = null;
      this.actStreamDataService.createEvent.eventAvailability = null;
      this.actStreamDataService.createEvent.eventImportance = null;
      this.actStreamDataService.moreOption.show = false;
    }
    /* close more options from event[end] */
  
    /* validate new event[start] */
    validateNewEvent(): boolean {
      this.isValidated = true;
      if (!this.actStreamDataService.createEvent.eventTitle) this.isValidated = false;
      if (!this.actStreamDataService.msgEditor.text) this.isValidated = false;
      if (!this.actStreamDataService.createEvent.eventStart) this.isValidated = false;
      if (!this.actStreamDataService.createEvent.eventEnd) this.isValidated = false;
      if (!this.actStreamDataService.createEvent.eventLocation) this.isValidated = false;
      if (this.actStreamDataService.toUsers.toUsers.length === 0 && !this.actStreamDataService.toUsers.toAllEmployee) this.isValidated = false;
      if (this.reminderOpt === true) {
        if (this.count === '') this.isValidated = false;
        if (this.type === '') this.isValidated = false;
      }
      return this.isValidated;
    }
    /* validate new event[end] */
  
    /* Create event [Start]*/
    createNewEvent(): void {
      if (!this.validateNewEvent()) return;
      this.actStreamDataService.createEvent.action = 'create'
      this.actStreamDataService.createEvent.eventDesc = this.actStreamDataService.msgEditor.text;
      // this.actStreamDataService.createEvent.reminder = [];
      if (this.reminderOpt) {
        // this.actStreamDataService.createEvent.reminder = [];
        // this.actStreamDataService.createEvent.reminder.push({ 'type': this.type, 'count': this.count })
        this.actStreamDataService.createEvent.reminder.type = this.type;
      this.actStreamDataService.createEvent.reminder.count = this.count
      }
      if (!this.actStreamDataService.moreOption.show) {
        this.actStreamDataService.createEvent.eventAvailability = null;
        this.actStreamDataService.createEvent.eventRepeat = null;
        this.actStreamDataService.createEvent.eventImportance = null;
      }
      this.actStreamDataService.createEvent.eventStart = this.utilityService.convertToUnix(this.actStreamDataService.createEvent.eventStart);
      this.actStreamDataService.createEvent.eventEnd = this.utilityService.convertToUnix(this.actStreamDataService.createEvent.eventEnd);
      this.activitySandboxService.createNewEvent();
      this.userlistingdrop = false;
      this.type = '';
      this.count = '';
      this.reminderOpt = false;
    }
    /* Create event [end]*/
  
    /* Update Event [Start] */
    updateEvent(): void {
      if (!this.validateNewEvent()) return;
      this.actStreamDataService.createEvent.action = 'update';
      this.actStreamDataService.createEvent.eventDesc = this.actStreamDataService.msgEditor.text;
      if (this.reminderOpt) {
        // this.actStreamDataService.createEvent.reminder = [];
        // this.actStreamDataService.createEvent.reminder.push({ 'type': this.type, 'count': this.count })
        this.actStreamDataService.createEvent.reminder.type = this.type;
      this.actStreamDataService.createEvent.reminder.count = this.count
      }
      this.actStreamDataService.createEvent.eventStart = this.utilityService.convertToUnix(this.actStreamDataService.createEvent.eventStart);
      this.actStreamDataService.createEvent.eventEnd = this.utilityService.convertToUnix(this.actStreamDataService.createEvent.eventEnd);
      this.activitySandboxService.createNewEvent();
      this.userlistingdrop = false;
    }
    /* Update Event [end] */
  
    /*  cancel create event[start]*/
    cancelEvent(): void {
      this.isValidated = true;
      this.userlistingdrop = false;
      this.type = '';
      this.count = '';
      this.reminderOpt = false;
      this.calendarDataService.createPopup.showPopup = false;
    }
    /* cancel create event[end] */

}

import { Injectable } from '@angular/core';
import { Subject } from 'rxjs/Subject';
import { CookieService } from 'ngx-cookie-service';

@Injectable()
export class DataService {

    fbl_fixed: any;
    nav_head_height: any;
    nameChange: Subject<string> = new Subject<string>();
    constructor(
        private cookieService: CookieService
    ) {
        
    }
    template:any = {
        formElements: {
            'singleLineText': false,
            'number': false,
            'checkboxes': false,
            'multipleChoice': false,
            'dropdown': false,
            'paragraphText': false,
            'section': false,
            'page': false,
            'name': false,
            'fileUpload': false,
            'address': false,
            'date': false,
            'email': false,
            'time': false,
            'website': false,
            'phone': false,
            'price': false,
            'likert': false,
            'rating': false,
            'focusSelect': false,
        },
        formElementToggle: {
            activeElement: false,
            activeIndex: ''
        },

        hrmformElementToggle: {
            activeElement: false,
            activeIndex: ''
        },

        deletePopup:{
            show:false
        },

        deleteCurrentElementIndex:{
            index:''
        },

        formPages: {
            total: 0

        },

        formValidation:{
            validating:false
        },
        hrmformValidation:{
            validating:false
        },

        createForm: {
            title: "",
            description: "",
            action: 'create',
            formAccessType: "",
            formStatus: "",
            isTemplate: false,
            isArchived: false,
            isPublished: false,
            allowMultiSubmit: false,
            formSlug: '',
            formComponents: []
        },
        createHrmForm: {
            title: "",
            description: "",
            action: 'create',
            formAccessType: "internal",
            formStatus: "",
            isTemplate: false,
            isArchived: false,
            isPublished: false,
            allowMultiSubmit: false,
            formSlug: '',
            formComponents: []
        },

        createFormPartial: {
            title: "",
            description: "",
            action: 'create',
            formAccessType: "internal",
            formStatus: "",
            isTemplate: false,
            isArchived: false,
            isPublished: false,
            allowMultiSubmit: false,
            formSlug: '',
        },
        createHrmFormPartial: {
            title: "",
            description: "",
            action: 'create',
            formAccessType: "internal",
            formStatus: "",
            isTemplate: false,
            isArchived: false,
            isPublished: false,
            allowMultiSubmit: false,
            formSlug: '',
        },

        formAPICall:{
            inProgress:false
        },
       hrmformAPICall:{
            inProgress:false
        },

        
        getAllForms: {
            formListsDeatils:null,
            page: 1,
            perPage: 10,
            searchText: null,
            sortOrder: 'desc',
            sortBy: 'createdAt',
            tab: ''
        },

        formShare: {
            formSlug: '',
            formTitle:'',
            sharedUsers: [
                
            ],    
            sendUsers:[

            ],
        },

        sharedUsers: {
            option: '',
            formSlug: '',
            formTitle:'',
            sharedUserList: [
                
            ],    
        },
        sendUsers: {
            option: '',
            formSlug: '',
            formTitle:'',
            sendUserList: [
                
            ],    
        },

        formSend: {
            formSlug: "",
            orgSlug: this.cookieService.get('orgSlug'),
            formStatus: "active",
            isPublished: true,
            sendUsers: []
          },

        formResponseManagement: {
            selectedAnswerSlug:'',
            reponseDetails: {},
            selectedResponseDetails: null,
            pageSlugs: [],
            selectdPageSlug: '',
            selectedPage: {},
        },
        
        showDate:{
            show:false
        },
        answerDate:{
            day:'',
            month:'',
            year:''
        },
        countryTemplate:{
            countries:[]
        },
        viewForm: {
            selctedFormSlug: '',
            selctedAnsSlug: '',
            permission:'view',
            selectedFormDetails: null,
            pageSlugs: [],
            selectdPageSlug: '',
            selectedPage: {},
            showSubmit: false,
            formResponse: {
                trainingRequestSlug: '',
                formComponents: []
            },
            updatedStatus:'',
            unPublishmodal: {
                show: false
            },
            previewOnlyElements:[],
            previewOnlyShow:false,
        },
        viewhrmForm: {
            selctedFormSlug: '',
            selctedAnsSlug: '',
            permission:'view',
            selectedFormDetails: null,
            pageSlugs: [],
            selectdPageSlug: '',
            selectedPage: {},
            showSubmit: false,
            formResponse: {
                formComponents: []
            },
            updatedStatus:'',
            unPublishmodal: {
                show: false
            },
            previewOnlyElements:[],
            previewOnlyShow:false,
        },

        viewSubmitForm:{
            formSlug:''
        },

        formsSelectionManagement: {
            selectAll: false,
            selectedFormSlugs: []
        }
    };
    newForm = {
        new_form: {
            show: false
        },
        fbl_fixed: {
            show: false
        },
        saveHrm :{
            show:false
         },
        frmBtn :{
            show:true
        },
        saveCourseHrm:{
            show:false
        }
    };
    newHrmForm = {
        hrmnew_form: {
            show: false
        },
        fbl_fixed: {
            show: false
        },
    };
    publishForm = {
        publish_form: {
            show: false
        },
        formAdminmodal: {
            show: false
        },
    };
    hrmpublishForm = {
        hrmpublish_form: {
            show: false
        },
        hrmformAdminmodal: {
            show: false
        },
    };
    formPreviewmodal = {
        form_preview_modal: {
            show: false
        },

        form_preview: {
            show: false
        },
        form_preview1: {
            show: false
        },
        form_preview2: {
            show: false
        },
        form_adminview: {
            show: false
        },
    };
    formListingmodals = {
        shareOption: {
            show: false,
           
        },
        sendToOption:{
            show:false
        },
        moreOption: {
            show: false
        },
        footerOption: {
            show: false
        },
        maxIncheck: {
            show: false
        },
        confirmPop: {
            show: false
        },

    

    };
    

    deletePopup = { ...this.template.deletePopup};
    deleteCurrentElementIndex = {...this.template.deleteCurrentElementIndex};
    showDate = { ...this.template.showDate };
    answerDate = { ...this.template.answerDate };
    form_preview_modal = { ...this.formPreviewmodal.form_preview_modal };
    form_preview = { ...this.formPreviewmodal.form_preview}
    form_adminview = { ...this.formPreviewmodal.form_adminview };
    form_preview2 = { ...this.formPreviewmodal.form_preview2 };
    form_preview1 = { ...this.formPreviewmodal.form_preview1 };
    footerOption = { ...this.formListingmodals.footerOption };
    maxIncheck = { ...this.formListingmodals.maxIncheck };
    shareOption = { ...this.formListingmodals.shareOption };
    sendToOption = { ...this.formListingmodals.sendToOption};
    moreOption = { ...this.formListingmodals.moreOption };
    confirmPop = { ...this.formListingmodals.confirmPop };
    new_form = { ...this.newForm.new_form };
    hrmnew_form = { ...this.newHrmForm. hrmnew_form};

    formPages = { ...this.template.formPages };
    publish_form = { ...this.publishForm.publish_form };
   hrmpublish_form = { ...this.hrmpublishForm.hrmpublish_form };

    formAdminmodal = { ...this.publishForm.formAdminmodal };
    hrmformAdminmodal = { ...this.hrmpublishForm.hrmformAdminmodal };
    formArray = [];
    formElementArray = [];
    formElements = { ...this.template.formElements };
    formElementToggle = { ... this.template.formElementToggle };
    hrmformElementToggle = { ... this.template.hrmformElementToggle };

    createForm = { ...this.template.createForm };
    createHrmForm = {...this.template.createHrmForm}
    createFormPartial = { ...this.template.createFormPartial };
    createHrmFormPartial = {...this.template.createHrmFormPartial}
    getAllForms = { ...this.template.getAllForms };
    viewForm:any = { ...this.template.viewForm };
    viewhrmForm:any = { ...this.template.viewhrmForm };
    
    countryTemplate = { ...this.template.countryTemplate };

    viewSubmitForm ={...this.template.viewSubmitForm};
    formsSelectionManagement = { ...this.template.formsSelectionManagement };
    formShare = { ...this.template.formShare };
    sharedUsers = { ...this.template.sharedUsers };
    sendUsers = { ...this.template.sendUsers};
    sharedWithUsers = { ...this.template.sharedWithUsers }
    formSend = { ...this.template.formSend };
    formResponseManagement = { ...this.template.formResponseManagement };
    formAPICall  = { ...this.template.formAPICall };
    hrmformAPICall = {...this.template.hrmformAPICall}
    formValidation  = { ...this.template.formValidation };
    hrmformValidation  = { ...this.template.hrmformValidation };

    saveHrm = { ...this.newForm.saveHrm};
    frmBtn = {...this.newForm.frmBtn};
    saveCourseHrm = {...this.newForm.saveCourseHrm};
    

    reset() {
        this.formElements = { ...this.template.formElements };
        this.formArray = [];
        this.formElementArray = [];
    }

    /*reset form after create or edit */
    resetForm(): void {
        this.createForm = { ...this.template.createForm };
        this.createForm.formComponents = [];
        this.formElementArray = [];
        this.formValidation  = { ...this.template.formValidation };    

    }


    /*reset form after create or edit */
    hrmresetForm(): void {
        this. createHrmForm = { ...this.template.createHrmForm};
        this.createHrmForm.formComponents = [];
        this.formElementArray = [];
        this.formValidation  = { ...this.template.formValidation };    

    }

    /*reset form after create or edit */
    resetFormView(): void {
        this.viewForm = { ...this.template.viewForm };
        this.viewForm.formResponse.formComponents = [];
    }

    resetGetAllForms():void{
        this.getAllForms = {...this.template.getAllForms};
    }

    /*reset form after create or edit */
    resetFormResponse(): void {
        this.template.formResponseManagement.selectedPage = {};
        this.formResponseManagement = { ...this.template.formResponseManagement };
        
    }

    /*reset form share */
    resetFormshare(): void {
        this.template.sharedUsers.sharedUserList = [];
        this.formShare = { ...this.template.formShare };
        this.sharedUsers = { ...this.template.sharedUsers };
    }

    /*reset form share */
    resetFormSend(): void {
        this.template.formSend.sendUsers = [];
        this.formSend = { ...this.template.formSend };
    }

    resetFormSelectionManagement():void{
        this.template.formsSelectionManagement.selectedFormSlugs = [];
        this.formsSelectionManagement = { ...this.template.formsSelectionManagement };
    }
}

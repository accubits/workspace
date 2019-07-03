import { ReviewRequestComponent } from './review-request/review-request.component';
import { KraModulesComponent } from './kra-modules/kra-modules.component';
import { FeedbackComponent } from './feedback/feedback.component';
import { OngoingTrainingComponent } from './ongoing-training/ongoing-training.component';
import { MyTrainingComponent } from './my-training/my-training.component';
import { LeaveTableComponent } from './leave-table/leave-table.component';
import { ApproveTableComponent } from './approve-table/approve-table.component';
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { HrmComponent} from './hrm.component';
import { EmployeeComponent} from './employee/employee.component';
import { LeaveComponent} from './leave/leave.component';
import { CompanyComponent} from './company/company.component';
import { EmpTableComponent} from './emp-table/emp-table.component';
import { AbsenceChartComponent} from './absence-chart/absence-chart.component';
import { HolidaysComponent} from './holidays/holidays.component';
import { LeaveTypeComponent} from './leave-type/leave-type.component';
import { TrainingComponent} from './training/training.component';
import { TrainingRequestTableComponent } from './training-request-table/training-request-table.component';
import { CompletedTrainingComponent } from './completed-training/completed-training.component';
import { PerformanceComponent} from './performance/performance.component';
import { ReviewHistoryComponent } from './review-history/review-history.component';
import { AppraisalCycleComponent} from './appraisal-cycle/appraisal-cycle.component';
import { MyPerformanceComponent } from './my-performance/my-performance.component';
import { OverviewComponent } from './overview/overview.component';
import { FormCreationWrapComponent } from './form-creation-wrap/form-creation-wrap.component';
import { SettingsComponent } from './settings/settings.component';
import { AppraisalFormComponent } from './appraisal-form/appraisal-form.component';

const routes: Routes = [
  {
    path: '',
    component: HrmComponent,
    children: [
      { path: '', redirectTo: 'employee', pathMatch: 'full'},
      { path: 'employee', component: EmployeeComponent,

      children: [
        { path: '', redirectTo: 'company', pathMatch: 'full'},
        { path: 'company', component: CompanyComponent} ,
        { path: 'emp-table', component: EmpTableComponent}
      ]
    },


      { path: 'leave', component: LeaveComponent,

      children: [
        { path: '', redirectTo: 'leave-table', pathMatch: 'full'},
        { path: 'leave-table' , component: LeaveTableComponent},
        { path: 'approve-table' , component: ApproveTableComponent},
        { path: 'absence' , component: AbsenceChartComponent},
        { path: 'holidays' , component: HolidaysComponent},
        { path: 'leave-type' , component: LeaveTypeComponent}
      ]
    },
        { path: 'training', component: TrainingComponent,

    children: [
      { path: '', redirectTo: 'my-training', pathMatch: 'full'},
      { path: 'my-training' , component: MyTrainingComponent},
      { path: 'training-request-table' , component: TrainingRequestTableComponent},
      { path: 'ongoing-training' , component: OngoingTrainingComponent},
      { path: 'completed-training' , component: CompletedTrainingComponent},
      { path: 'feedback' , component: FeedbackComponent},
      { path: 'overview' , component: OverviewComponent}
    ]
  },
  { path: 'performance', component: PerformanceComponent,
  children: [
    { path: '', redirectTo: 'review-request', pathMatch: 'full'},
    { path: 'review-request', component: ReviewRequestComponent},
    { path: 'review-history', component: ReviewHistoryComponent},
    { path: 'my-performance', component: MyPerformanceComponent},
    { path: 'kra-modules' , component: KraModulesComponent},
    { path: 'appraisal-cycle' , component: AppraisalCycleComponent,

      children: [
        { path: 'form', component: AppraisalFormComponent}
      ]
    },
  ]
  },
  { path: 'settings', component: SettingsComponent,

  children: [
    { path: '', redirectTo: 'settings', pathMatch: 'full'},
  ]
},
  { path: 'form_creation', component: FormCreationWrapComponent }

    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class HrmRoutingModule { }

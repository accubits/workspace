import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { FormsComponent } from './forms.component';
import { FormOverviewComponent } from './form-overview/form-overview.component';
import { FormSubmitModalComponent } from './form-submit-modal/form-submit-modal.component';
import { FormIndiResponseComponent } from './form-indi-response/form-indi-response.component';
import { FormListingWrapComponent } from './form-listing-wrap/form-listing-wrap.component';
import { FormCreationComponent } from './form-creation/form-creation.component';
import { SharedWithMeComponent } from './shared-with-me/shared-with-me.component';
import { DraftsComponent } from './drafts/drafts.component';
import { PublishedComponent } from './published/published.component';
import { InactiveComponent } from './inactive/inactive.component';


const routes: Routes = [
  {
    path: '', component: FormsComponent,
    children: [
      {
        path: 'form_listing_wrap', component: FormListingWrapComponent,
        children: [
          { path: 'shared_with_me', component: SharedWithMeComponent },
          { path: 'published', component: PublishedComponent },
          { path: 'drafts', component: DraftsComponent },
          { path: 'inactive', component: InactiveComponent },
          // { path: 'formOverview', componen0t: FormOverviewComponent },
          // { path: 'formndiviualResponse', component: FormIndiResponseComponent },
          { path: '', pathMatch: 'full', redirectTo: 'shared_with_me' },
        ]
      },

      { path: 'form_creation', component: FormCreationComponent },
      { path: 'form_submit/:formSlug/:ansSlug', component: FormSubmitModalComponent },
      { path: '', pathMatch: 'full', redirectTo: 'form_listing_wrap' },
      { path: 'formOverview', component: FormOverviewComponent },
      { path: 'formndiviualResponse', component: FormIndiResponseComponent },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class FormsRoutingModule { }

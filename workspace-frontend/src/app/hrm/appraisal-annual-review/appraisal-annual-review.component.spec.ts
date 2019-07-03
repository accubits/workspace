import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AppraisalAnnualReviewComponent } from './appraisal-annual-review.component';

describe('AppraisalAnnualReviewComponent', () => {
  let component: AppraisalAnnualReviewComponent;
  let fixture: ComponentFixture<AppraisalAnnualReviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AppraisalAnnualReviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AppraisalAnnualReviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

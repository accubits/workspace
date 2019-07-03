import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AnnualReviewResultDetailComponent } from './annual-review-result-detail.component';

describe('AnnualReviewResultDetailComponent', () => {
  let component: AnnualReviewResultDetailComponent;
  let fixture: ComponentFixture<AnnualReviewResultDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AnnualReviewResultDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AnnualReviewResultDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

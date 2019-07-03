import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AnnualReviewResultComponent } from './annual-review-result.component';

describe('AnnualReviewResultComponent', () => {
  let component: AnnualReviewResultComponent;
  let fixture: ComponentFixture<AnnualReviewResultComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AnnualReviewResultComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AnnualReviewResultComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

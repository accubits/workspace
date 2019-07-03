import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ReviewRequestDetailComponent } from './review-request-detail.component';

describe('ReviewRequestDetailComponent', () => {
  let component: ReviewRequestDetailComponent;
  let fixture: ComponentFixture<ReviewRequestDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ReviewRequestDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ReviewRequestDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

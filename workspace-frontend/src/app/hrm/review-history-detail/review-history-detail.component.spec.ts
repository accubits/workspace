import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ReviewHistoryDetailComponent } from './review-history-detail.component';

describe('ReviewHistoryDetailComponent', () => {
  let component: ReviewHistoryDetailComponent;
  let fixture: ComponentFixture<ReviewHistoryDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ReviewHistoryDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ReviewHistoryDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

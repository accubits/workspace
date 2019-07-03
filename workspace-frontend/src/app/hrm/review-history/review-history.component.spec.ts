import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ReviewHistoryComponent } from './review-history.component';

describe('ReviewHistoryComponent', () => {
  let component: ReviewHistoryComponent;
  let fixture: ComponentFixture<ReviewHistoryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ReviewHistoryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ReviewHistoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

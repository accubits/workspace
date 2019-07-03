import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RatingPreviewComponent } from './rating-preview.component';

describe('RatingPreviewComponent', () => {
  let component: RatingPreviewComponent;
  let fixture: ComponentFixture<RatingPreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RatingPreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RatingPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

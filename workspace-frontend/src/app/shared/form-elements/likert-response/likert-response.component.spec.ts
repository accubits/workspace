import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LikertResponseComponent } from './likert-response.component';

describe('LikertResponseComponent', () => {
  let component: LikertResponseComponent;
  let fixture: ComponentFixture<LikertResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LikertResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LikertResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

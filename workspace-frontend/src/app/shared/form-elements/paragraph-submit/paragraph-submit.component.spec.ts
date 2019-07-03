import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ParagraphSubmitComponent } from './paragraph-submit.component';

describe('ParagraphSubmitComponent', () => {
  let component: ParagraphSubmitComponent;
  let fixture: ComponentFixture<ParagraphSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ParagraphSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ParagraphSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

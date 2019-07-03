import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ParagraphResponseComponent } from './paragraph-response.component';

describe('ParagraphResponseComponent', () => {
  let component: ParagraphResponseComponent;
  let fixture: ComponentFixture<ParagraphResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ParagraphResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ParagraphResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
